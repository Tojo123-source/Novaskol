<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CommunicationController extends Controller
{
    public function index(ModuleRegistry $modules)
    {
        $this->ensureSession();

        return $this->view('modules.communication.index', $modules, 'communication');
    }

    public function privateChat(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $this->touchActivity();
        $selectedUserId = (int) $request->query('user', 0);
        $conversationId = $selectedUserId > 0 ? $this->privateConversationId($selectedUserId, true) : 0;

        $contacts = $this->contacts();

        return $this->view('modules.communication.private', $modules, 'chat_private', [
            'contacts' => $contacts,
            'selectedContact' => $contacts->firstWhere('id', $selectedUserId),
            'selectedUserId' => $selectedUserId,
            'conversationId' => $conversationId,
            'currentUserId' => $this->userId(),
            'currentUserRole' => $this->userRole(),
        ]);
    }

    public function groupChat(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $this->touchActivity();
        $this->ensureAnnouncementGroup();
        $selectedGroupId = (int) $request->query('group', 0);
        $permissions = $this->userPermissions();
        $canWriteChatGroup = $this->userRole() === 'admin' || (($permissions['chat_group'] ?? '') === 'ecriture');

        return $this->view('modules.communication.group', $modules, 'chat_group', [
            'groups' => $this->groups(),
            'users' => DB::table('utilisateurs')->select('id', 'nom', 'role', 'avatar')->where('id', '!=', $this->userId())->orderBy('nom')->get(),
            'selectedGroupId' => $selectedGroupId,
            'currentUserId' => $this->userId(),
            'currentUserRole' => $this->userRole(),
            'canManageGroups' => $canWriteChatGroup,
            'canWriteChatGroup' => $canWriteChatGroup,
        ]);
    }

    public function parentResponsibleChat(ModuleRegistry $modules)
    {
        $this->ensureSession();
        abort_unless($this->userRole() === 'parent', 403);

        return redirect()->route('modules.chat-prive');
    }

    public function privateConversation(Request $request)
    {
        $this->ensureSession();
        $data = $request->validate(['recipient_id' => ['required', 'integer']]);

        return ['success' => true, 'conversation_id' => $this->privateConversationId((int) $data['recipient_id'], true)];
    }

    public function privateContacts(Request $request)
    {
        $this->ensureSession();

        return [
            'success' => true,
            'contacts' => $this->contacts((string) $request->query('q', ''))->values(),
        ];
    }

    public function messages(Request $request, int $conversationId)
    {
        $this->ensureSession();
        abort_unless($this->canAccessConversation($conversationId), 403);
        $after = (int) $request->query('after', 0);
        $this->ensureTypingTable();
        DB::table('messages')->where('conversation_id', $conversationId)->where('sender_id', '!=', $this->userId())->update(['is_delivered' => 1, 'is_read' => 1]);

        return [
            'success' => true,
            'messages' => $this->fetchMessages($conversationId, $after),
            'typing_users' => $this->typingUsers($conversationId),
        ];
    }

    public function typing(Request $request, int $conversationId)
    {
        $this->ensureSession();
        abort_unless($this->canAccessConversation($conversationId, true), 403);
        $data = $request->validate([
            'typing' => ['required', 'boolean'],
        ]);

        $this->ensureTypingTable();

        if ($data['typing']) {
            DB::table('chat_typing_status')->updateOrInsert(
                ['conversation_id' => $conversationId, 'user_id' => $this->userId()],
                ['user_role' => $this->userRole(), 'updated_at' => now()]
            );
        } else {
            DB::table('chat_typing_status')
                ->where('conversation_id', $conversationId)
                ->where('user_id', $this->userId())
                ->delete();
        }

        return [
            'success' => true,
            'typing_users' => $this->typingUsers($conversationId),
        ];
    }

    public function unreadSummary()
    {
        $this->ensureSession();

        return [
            'success' => true,
            'unread' => $this->unreadMessagesCount(),
        ];
    }

    public function sendMessage(Request $request, int $conversationId)
    {
        $this->ensureSession();
        abort_unless($this->canAccessConversation($conversationId, true), 403);
        $request->validate([
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:51200'],
        ]);

        $content = trim((string) $request->input('content', ''));
        $filePath = $fileName = null;
        $fileSize = null;
        $type = 'text';

        if ($request->hasFile('file')) {
            [$filePath, $fileName, $fileSize, $type] = $this->uploadChatFile($request);
            if ($content === '') {
                $content = $type === 'image' ? '[Image envoyee]' : '[Fichier : '.$fileName.']';
            }
        }

        abort_if($content === '' && ! $filePath, 422, 'Message vide.');
        $id = DB::table('messages')->insertGetId([
            'conversation_id' => $conversationId,
            'sender_type' => $this->userRole(),
            'sender_id' => $this->userId(),
            'content' => $content,
            'type' => $type,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'created_at' => now(),
            'is_read' => 0,
            'is_delivered' => 0,
        ]);
        DB::table('conversations')->where('id', $conversationId)->update(['updated_at' => now()]);
        $this->ensureTypingTable();
        DB::table('chat_typing_status')
            ->where('conversation_id', $conversationId)
            ->where('user_id', $this->userId())
            ->delete();

        return ['success' => true, 'message' => $this->fetchMessages($conversationId, $id - 1)->first()];
    }

    public function updateMessage(Request $request, int $messageId)
    {
        $this->ensureSession();
        $data = $request->validate(['content' => ['required', 'string', 'max:5000']]);
        $message = DB::table('messages')->where('id', $messageId)->first();
        abort_unless($message && (int) $message->sender_id === $this->userId() && $this->canAccessConversation((int) $message->conversation_id, true), 403);

        DB::table('messages')->where('id', $messageId)->update(['content' => trim($data['content'])]);

        return ['success' => true, 'message' => $this->fetchMessages((int) $message->conversation_id, $messageId - 1)->first()];
    }

    public function deleteMessage(int $messageId)
    {
        $this->ensureSession();
        $message = DB::table('messages')->where('id', $messageId)->first();
        abort_unless($message && (int) $message->sender_id === $this->userId() && $this->canAccessConversation((int) $message->conversation_id, true), 403);

        DB::table('messages')->where('id', $messageId)->delete();
        DB::table('conversations')->where('id', $message->conversation_id)->update(['updated_at' => now()]);

        return ['success' => true, 'id' => $messageId];
    }

    public function storeGroup(Request $request)
    {
        $this->ensureSession();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'members' => ['array'],
            'members.*' => ['integer'],
            'avatar' => ['nullable', 'image', 'max:5120'],
        ]);

        $groupId = DB::transaction(function () use ($request, $data) {
            $avatar = $this->uploadGroupAvatar($request);
            $id = DB::table('conversations')->insertGetId([
                'type' => 'group',
                'name' => $data['name'],
                'creator_id' => $this->userId(),
                'avatar' => $avatar,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->syncGroupMembers($id, $data['members'] ?? []);
            return $id;
        });

        return redirect()->route('modules.chat-groupe', ['group' => $groupId])->with('communication_msg', ['type' => 'success', 'text' => 'Groupe cree avec succes.']);
    }

    public function updateGroup(Request $request, int $groupId)
    {
        $this->ensureSession();
        $group = DB::table('conversations')->where('id', $groupId)->where('type', 'group')->first();
        abort_unless($group && (int) $group->creator_id === $this->userId(), 403);
        abort_if($this->isAnnouncementGroup($groupId), 403, 'Le canal officiel ne peut pas etre modifie ici.');
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'members' => ['array'],
            'members.*' => ['integer'],
            'avatar' => ['nullable', 'image', 'max:5120'],
        ]);

        DB::transaction(function () use ($request, $data, $group, $groupId) {
            $avatar = $this->uploadGroupAvatar($request, $group->avatar);
            DB::table('conversations')->where('id', $groupId)->update(['name' => $data['name'], 'avatar' => $avatar, 'updated_at' => now()]);
            $this->syncGroupMembers($groupId, $data['members'] ?? []);
        });

        return redirect()->route('modules.chat-groupe', ['group' => $groupId])->with('communication_msg', ['type' => 'success', 'text' => 'Groupe modifie avec succes.']);
    }

    public function deleteGroup(int $groupId)
    {
        $this->ensureSession();
        $group = DB::table('conversations')->where('id', $groupId)->where('type', 'group')->first();
        abort_unless($group && (int) $group->creator_id === $this->userId(), 403);
        abort_if($this->isAnnouncementGroup($groupId), 403, 'Le canal officiel ne peut pas etre supprime.');
        DB::transaction(function () use ($group, $groupId) {
            DB::table('messages')->where('conversation_id', $groupId)->delete();
            DB::table('conversation_participants')->where('conversation_id', $groupId)->delete();
            DB::table('conversations')->where('id', $groupId)->delete();
            if ($group->avatar && File::exists(public_path('legacy/uploads/group_avatars/'.$group->avatar))) {
                File::delete(public_path('legacy/uploads/group_avatars/'.$group->avatar));
            }
        });

        return redirect()->route('modules.chat-groupe')->with('communication_msg', ['type' => 'success', 'text' => 'Groupe supprime avec succes.']);
    }

    private function privateConversationId(int $recipientId, bool $create): int
    {
        if ($recipientId === $this->userId()) {
            return 0;
        }
        $recipientRole = DB::table('utilisateurs')->where('id', $recipientId)->value('role');
        if (! $recipientRole) {
            return 0;
        }

        if (! $this->canStartPrivateConversation($this->userRole(), $recipientRole, $recipientId, $this->userId())) {
            return 0;
        }

        $conversation = DB::table('conversations as c')
            ->join('conversation_participants as a', function ($join) {
                $join->on('a.conversation_id', '=', 'c.id')->where('a.user_id', '=', $this->userId())->where('a.user_type', '=', $this->userRole());
            })
            ->join('conversation_participants as b', function ($join) use ($recipientId, $recipientRole) {
                $join->on('b.conversation_id', '=', 'c.id')->where('b.user_id', '=', $recipientId)->where('b.user_type', '=', $recipientRole);
            })
            ->where('c.type', 'private')
            ->select('c.id')
            ->first();

        if ($conversation || ! $create) {
            return (int) ($conversation->id ?? 0);
        }

        return DB::transaction(function () use ($recipientId, $recipientRole) {
            $id = DB::table('conversations')->insertGetId(['type' => 'private', 'name' => null, 'creator_id' => $this->userId(), 'created_at' => now(), 'updated_at' => now()]);
            DB::table('conversation_participants')->insert([
                ['conversation_id' => $id, 'user_type' => $this->userRole(), 'user_id' => $this->userId(), 'joined_at' => now()],
                ['conversation_id' => $id, 'user_type' => $recipientRole, 'user_id' => $recipientId, 'joined_at' => now()],
            ]);
            return $id;
        });
    }

    private function contacts(string $search = '')
    {
        $responsibleStaffIds = $this->responsibleStaffUsers()->pluck('id')->map(fn ($id) => (int) $id)->all();
        $contacts = DB::table('utilisateurs')
            ->select('id', 'nom', 'email', 'role', 'avatar', 'last_activity')
            ->where('id', '!=', $this->userId())
            ->when(in_array($this->userRole(), ['admin', 'enseignant'], true), fn ($query) => $query->where('role', '!=', 'parent'))
            ->when($this->userRole() === 'staff' && ! $this->isResponsibleStaff($this->userId()), fn ($query) => $query->where('role', '!=', 'parent'))
            ->when($this->userRole() === 'parent', fn ($query) => $query->whereIn('id', $responsibleStaffIds))
            ->when($this->userRole() === 'staff' && $this->isResponsibleStaff($this->userId()), fn ($query) => $query->where(function ($q) {
                $q->where('role', '!=', 'parent')
                    ->orWhereExists(function ($exists) {
                        $exists->select(DB::raw(1))
                            ->from('parent_eleves as pe')
                            ->whereColumn('pe.parent_user_id', 'utilisateurs.id');
                    });
            }))
            ->when(trim($search) !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->orderBy('nom')
            ->limit(30)
            ->get();
        $unread = DB::table('conversations as c')
            ->join('conversation_participants as mine', function ($join) {
                $join->on('mine.conversation_id', '=', 'c.id')->where('mine.user_id', '=', $this->userId());
            })
            ->join('conversation_participants as other', function ($join) {
                $join->on('other.conversation_id', '=', 'c.id')->where('other.user_id', '!=', $this->userId());
            })
            ->leftJoin('messages as m', function ($join) {
                $join->on('m.conversation_id', '=', 'c.id')->where('m.sender_id', '!=', $this->userId())->where('m.is_read', '=', 0);
            })
            ->where('c.type', 'private')
            ->groupBy('other.user_id')
            ->select('other.user_id', DB::raw('COUNT(m.id) as unread_count'))
            ->pluck('unread_count', 'user_id');

        return $contacts->map(function ($contact) use ($unread) {
            $contact->avatar_url = $this->avatarUrl($contact->avatar);
            $contact->unread_count = (int) ($unread[$contact->id] ?? 0);
            $contact->is_online = $contact->last_activity && Carbon::parse($contact->last_activity)->gt(now()->subMinutes(5));
            return $contact;
        });
    }

    private function groups()
    {
        return DB::table('conversations as c')
            ->join('conversation_participants as cp', 'cp.conversation_id', '=', 'c.id')
            ->leftJoin('messages as m', function ($join) {
                $join->on('m.conversation_id', '=', 'c.id')->where('m.sender_id', '!=', $this->userId())->where('m.is_read', '=', 0);
            })
            ->where('c.type', 'group')
            ->where('cp.user_id', $this->userId())
            ->where('cp.user_type', $this->userRole())
            ->groupBy('c.id', 'c.name', 'c.creator_id', 'c.avatar', 'c.is_announcement', 'c.updated_at')
            ->select('c.*', DB::raw('COUNT(m.id) as unread_count'))
            ->orderByDesc('c.is_announcement')
            ->orderByDesc('c.updated_at')
            ->get();
    }

    private function unreadMessagesCount(): int
    {
        return DB::table('messages as m')
            ->join('conversation_participants as cp', 'cp.conversation_id', '=', 'm.conversation_id')
            ->where('cp.user_id', $this->userId())
            ->where('cp.user_type', $this->userRole())
            ->where('m.sender_id', '!=', $this->userId())
            ->where('m.is_read', 0)
            ->count();
    }

    private function fetchMessages(int $conversationId, int $after = 0)
    {
        $messages = DB::table('messages as m')
            ->leftJoin('utilisateurs as u', 'u.id', '=', 'm.sender_id')
            ->select('m.*', 'u.nom as sender_name', 'u.avatar')
            ->where('m.conversation_id', $conversationId)
            ->when($after > 0, fn ($query) => $query->where('m.id', '>', $after))
            ->orderBy('m.id')
            ->limit($after > 0 ? 100 : 200)
            ->get();

        return $messages->map(function ($message) {
            $message->mine = (int) $message->sender_id === $this->userId();
            $message->sender_name = $message->sender_name ?: 'Utilisateur #'.$message->sender_id;
            $message->avatar_url = $this->avatarUrl($message->avatar);
            $message->file_url = $message->file_path ? asset('legacy/'.ltrim($message->file_path, '/')) : null;
            $message->created_label = $message->created_at ? (string) $message->created_at : '';
            return $message;
        });
    }

    private function syncGroupMembers(int $groupId, array $members): void
    {
        DB::table('conversation_participants')->where('conversation_id', $groupId)->delete();
        $ids = array_unique(array_merge([$this->userId()], array_map('intval', $members)));
        foreach ($ids as $id) {
            $role = DB::table('utilisateurs')->where('id', $id)->value('role');
            if ($role) {
                DB::table('conversation_participants')->insert(['conversation_id' => $groupId, 'user_type' => $role, 'user_id' => $id, 'joined_at' => now()]);
            }
        }
    }

    private function ensureAnnouncementGroup(): void
    {
        if (! DB::getSchemaBuilder()->hasColumn('conversations', 'is_announcement')) {
            return;
        }

        DB::transaction(function () {
            $group = DB::table('conversations')
                ->where('type', 'group')
                ->where('is_announcement', 1)
                ->first();

            if (! $group) {
                $group = DB::table('conversations')
                    ->where('type', 'group')
                    ->where('name', 'General - Ecole')
                    ->orWhere(function ($query) {
                        $query->where('type', 'group')->where('name', 'Général - École');
                    })
                    ->first();
            }

            if (! $group) {
                $creatorId = (int) (DB::table('utilisateurs')->where('role', 'admin')->orderBy('id')->value('id') ?: $this->userId());
                $groupId = DB::table('conversations')->insertGetId([
                    'type' => 'group',
                    'name' => 'General - Ecole',
                    'creator_id' => $creatorId,
                    'avatar' => null,
                    'is_announcement' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $groupId = (int) $group->id;
                DB::table('conversations')->where('id', $groupId)->update(['is_announcement' => 1]);
            }

            $this->syncAnnouncementMembers($groupId);
            $this->ensureParentChatGroupPermissions();
        });
    }

    private function syncAnnouncementMembers(int $groupId): void
    {
        DB::table('conversation_participants')->where('conversation_id', $groupId)->delete();

        DB::table('utilisateurs')->select('id', 'role')->orderBy('id')->chunk(200, function ($users) use ($groupId) {
            foreach ($users as $user) {
                if (! in_array($user->role, ['admin', 'enseignant', 'staff', 'parent'], true)) {
                    continue;
                }

                DB::table('conversation_participants')->insert([
                    'conversation_id' => $groupId,
                    'user_type' => $user->role,
                    'user_id' => (int) $user->id,
                    'joined_at' => now(),
                ]);
            }
        });
    }

    private function ensureParentChatGroupPermissions(): void
    {
        DB::table('utilisateurs')
            ->where('role', 'parent')
            ->select('id')
            ->orderBy('id')
            ->chunk(200, function ($parents) {
                foreach ($parents as $parent) {
                    DB::table('permissions')->updateOrInsert(
                        ['utilisateur_id' => (int) $parent->id, 'module' => 'chat_group'],
                        ['role' => 'parent', 'acces' => 'lecture']
                    );
                }
            });
    }

    private function uploadChatFile(Request $request): array
    {
        $file = $request->file('file');
        File::ensureDirectoryExists(public_path('legacy/uploads/chat_files'));
        $extension = strtolower($file->getClientOriginalExtension());
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $stored = time().'_'.Str::random(16).'.'.$extension;
        $file->move(public_path('legacy/uploads/chat_files'), $stored);
        $type = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'], true) ? 'image' : 'file';

        return ['uploads/chat_files/'.$stored, $fileName, $fileSize, $type];
    }

    private function uploadGroupAvatar(Request $request, ?string $existing = null): ?string
    {
        if (! $request->hasFile('avatar')) {
            return $existing;
        }
        $file = $request->file('avatar');
        File::ensureDirectoryExists(public_path('legacy/uploads/group_avatars'));
        $stored = time().'_'.Str::random(16).'.'.$file->getClientOriginalExtension();
        $file->move(public_path('legacy/uploads/group_avatars'), $stored);
        if ($existing && File::exists(public_path('legacy/uploads/group_avatars/'.$existing))) {
            File::delete(public_path('legacy/uploads/group_avatars/'.$existing));
        }
        return $stored;
    }

    private function canAccessConversation(int $conversationId, bool $write = false): bool
    {
        $conversation = DB::table('conversation_participants as cp')
            ->join('conversations as c', 'c.id', '=', 'cp.conversation_id')
            ->where('cp.conversation_id', $conversationId)
            ->where('cp.user_id', $this->userId())
            ->where('cp.user_type', $this->userRole())
            ->select('c.type', 'c.is_announcement')
            ->first();

        if (! $conversation) {
            return false;
        }

        if ($this->userRole() === 'admin') {
            return true;
        }

        if ($conversation->type === 'group' && (int) ($conversation->is_announcement ?? 0) === 1) {
            return ! $write;
        }

        if ($conversation->type === 'private' && $this->conversationHasParent($conversationId)) {
            return ($this->userRole() === 'parent' && $this->conversationHasResponsibleStaff($conversationId))
                || ($this->userRole() === 'staff' && $this->isResponsibleStaff($this->userId()));
        }

        $module = $conversation->type === 'group' ? 'chat_group' : 'chat_private';
        $access = DB::table('permissions')
            ->where('utilisateur_id', $this->userId())
            ->where('module', $module)
            ->value('acces');

        return $write ? $access === 'ecriture' : in_array($access, ['lecture', 'ecriture'], true);
    }

    private function isAnnouncementGroup(int $conversationId): bool
    {
        if (! DB::getSchemaBuilder()->hasColumn('conversations', 'is_announcement')) {
            return false;
        }

        return (bool) DB::table('conversations')
            ->where('id', $conversationId)
            ->where('type', 'group')
            ->where('is_announcement', 1)
            ->exists();
    }

    private function canStartPrivateConversation(string $currentRole, string $recipientRole, int $recipientId, int $currentUserId): bool
    {
        if ($currentRole === 'parent' || $recipientRole === 'parent') {
            return ($currentRole === 'parent' && $recipientRole === 'staff' && $this->isResponsibleStaff($recipientId))
                || ($currentRole === 'staff' && $this->isResponsibleStaff($currentUserId) && $recipientRole === 'parent');
        }

        return true;
    }

    private function responsibleStaffUsers()
    {
        return DB::table('utilisateurs as u')
            ->join('staff as s', 's.email', '=', 'u.email')
            ->leftJoin('roles as r', 'r.id', '=', 's.role_id')
            ->leftJoin('departements as d', 'd.id', '=', 's.departement_id')
            ->where('u.role', 'staff')
            ->where(function ($q) {
                $q->whereRaw('LOWER(r.nom) = ?', ['assistant'])
                    ->orWhereRaw('LOWER(r.nom) LIKE ?', ['%assistant%']);
            })
            ->where(function ($q) {
                $q->whereRaw('LOWER(d.nom) = ?', ['administration'])
                    ->orWhereRaw('LOWER(d.nom) LIKE ?', ['%administration%']);
            })
            ->select('u.id', 'u.nom', 'u.email', 'u.role', 'u.avatar', 'u.last_activity', 'r.nom as staff_role', 'd.nom as departement')
            ->orderBy('u.nom')
            ->get()
            ->map(function ($user) {
                $user->avatar_url = $this->avatarUrl($user->avatar);
                $user->is_online = $user->last_activity && Carbon::parse($user->last_activity)->gt(now()->subMinutes(5));
                return $user;
            });
    }

    private function isResponsibleStaff(int $userId): bool
    {
        if ($userId <= 0) {
            return false;
        }

        return $this->responsibleStaffUsers()->contains(fn ($user) => (int) $user->id === $userId);
    }

    private function conversationHasParent(int $conversationId): bool
    {
        return DB::table('conversation_participants')
            ->where('conversation_id', $conversationId)
            ->where('user_type', 'parent')
            ->exists();
    }

    private function conversationHasResponsibleStaff(int $conversationId): bool
    {
        $staffIds = $this->responsibleStaffUsers()->pluck('id')->map(fn ($id) => (int) $id)->all();

        if (empty($staffIds)) {
            return false;
        }

        return DB::table('conversation_participants')
            ->where('conversation_id', $conversationId)
            ->where('user_type', 'staff')
            ->whereIn('user_id', $staffIds)
            ->exists();
    }

    private function avatarUrl(?string $avatar): string
    {
        $avatar = trim((string) $avatar);
        if ($avatar === '') {
            return asset('legacy/images/default-avatar.png');
        }
        if (str_starts_with($avatar, 'images/') || str_starts_with($avatar, 'uploads/')) {
            return asset('legacy/'.$avatar);
        }
        return asset('legacy/uploads/avatars/'.$avatar);
    }

    private function typingUsers(int $conversationId): array
    {
        $this->ensureTypingTable();

        return DB::table('chat_typing_status as t')
            ->join('utilisateurs as u', 'u.id', '=', 't.user_id')
            ->where('t.conversation_id', $conversationId)
            ->where('t.user_id', '!=', $this->userId())
            ->where('t.updated_at', '>=', now()->subSeconds(6))
            ->orderByDesc('t.updated_at')
            ->limit(3)
            ->pluck('u.nom')
            ->map(fn ($name) => (string) $name)
            ->all();
    }

    private function ensureTypingTable(): void
    {
        if (Schema::hasTable('chat_typing_status')) {
            return;
        }

        DB::statement('CREATE TABLE IF NOT EXISTS chat_typing_status (
            conversation_id BIGINT UNSIGNED NOT NULL,
            user_id BIGINT UNSIGNED NOT NULL,
            user_role VARCHAR(50) NULL,
            updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (conversation_id, user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    private function view(string $name, ModuleRegistry $modules, string $activeModule, array $data = [])
    {
        return view($name, $data + [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'activeModule' => $activeModule,
        ]);
    }

    private function touchActivity(): void
    {
        DB::table('utilisateurs')->where('id', $this->userId())->update(['last_activity' => now()]);
    }

    private function ensureSession(): void
    {
        abort_unless(session()->has('utilisateur') && in_array($this->userRole(), ['admin', 'enseignant', 'parent', 'staff'], true), 403);
    }

    private function userId(): int
    {
        return (int) session('utilisateur.id', 0);
    }

    private function userRole(): string
    {
        return (string) session('utilisateur.role', 'admin');
    }

    private function userPermissions(): array
    {
        return $this->userId() > 0 ? DB::table('permissions')->where('utilisateur_id', $this->userId())->pluck('acces', 'module')->all() : [];
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) ['nom' => 'Ecole', 'logo' => 'logo.png'];
    }
}
