<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use App\Services\Novaskol\RelationalDeleteService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StudentController extends Controller
{
    public function index(ModuleRegistry $modules)
    {
        $this->ensureSession();

        return view('modules.administration.inscription', [
            'modules' => $modules->all(),
            'userPermissions' => $this->userPermissions(),
            'ecole' => $this->school(),
            'classes' => DB::table('classes')->select('id', 'nom', 'niveau')->orderBy('niveau')->orderBy('nom')->get(),
            'currentYear' => $this->currentSchoolYear(),
        ]);
    }

    public function search(Request $request)
    {
        $this->ensureSession();

        $students = $this->studentsQuery($request)->get();

        return response()->json([
            'grid' => $this->renderGrid($students),
            'table' => $this->renderPrintRows($students),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureSession();

        $data = $this->validatedStudentData($request);
        $data['matricule'] = $this->generateMatricule($data['annee_scolaire']);
        $data['photo'] = $this->storePhoto($request) ?: 'uploads/eleves/default.jpg';
        $data['qr_token'] = app(QrCodeService::class)->generateUniqueToken();

        DB::transaction(function () use ($data) {
            $studentId = DB::table('eleves')->insertGetId($this->studentPayload($data));
            $this->upsertParents($data);
            $this->syncParentAccount($studentId, $data);
        });

        return response()->json(['status' => 'success', 'message' => 'Eleve inscrit avec succes !']);
    }

    public function update(Request $request, int $id)
    {
        $this->ensureSession();

        $student = DB::table('eleves')->where('id', $id)->first();
        abort_if(! $student, 404, 'Eleve introuvable.');

        $data = $this->validatedStudentData($request, true);
        $data['matricule'] = trim((string) $request->input('matricule', $student->matricule));
        $data['photo'] = $student->photo ?: 'uploads/eleves/default.jpg';

        if ($request->boolean('supprimer_photo')) {
            $this->deletePhoto($student->photo);
            $data['photo'] = 'uploads/eleves/default.jpg';
        }

        if ($newPhoto = $this->storePhoto($request)) {
            $this->deletePhoto($student->photo);
            $data['photo'] = $newPhoto;
        }

        DB::transaction(function () use ($id, $data) {
            DB::table('eleves')->where('id', $id)->update($this->studentPayload($data));
            $this->upsertParents($data);
            $this->syncParentAccount($id, $data);
        });

        return response()->json(['status' => 'success', 'message' => 'Eleve modifie avec succes !']);
    }

    public function destroy(int $id, RelationalDeleteService $deletions)
    {
        $this->ensureSession(['admin', 'enseignant']);

        DB::transaction(function () use ($id, $deletions) {
            $student = DB::table('eleves')->where('id', $id)->first();

            if (! $student) {
                abort(404, 'Eleve introuvable.');
            }

            $deletions->deleteStudentRelations($id);
            DB::table('eleves')->where('id', $id)->delete();
            $this->deletePhoto($student->photo);

            $remaining = DB::table('eleves')
                ->where('nom_pere', $student->nom_pere)
                ->where('nom_mere', $student->nom_mere)
                ->where('annee_scolaire', $student->annee_scolaire)
                ->count();

            if ($remaining === 0) {
                DB::table('parents')
                    ->where('nom_pere', $student->nom_pere)
                    ->where('nom_mere', $student->nom_mere)
                    ->where('annee_scolaire', $student->annee_scolaire)
                    ->delete();
            }
        });

        return response('ok');
    }

    public function parents(Request $request)
    {
        $this->ensureSession();

        $parent = DB::table('parents')
            ->where('nom_pere', (string) $request->query('nom_pere'))
            ->where('nom_mere', (string) $request->query('nom_mere'))
            ->where('annee_scolaire', (string) $request->query('annee_scolaire'))
            ->first();

        return response()->json([
            'telephone_pere' => $parent->telephone_pere ?? 'N/A',
            'telephone_mere' => $parent->telephone_mere ?? 'N/A',
            'profession_pere' => $parent->profession_pere ?? 'N/A',
            'profession_mere' => $parent->profession_mere ?? 'N/A',
            'adresse_pere' => $parent->adresse_pere ?? 'N/A',
            'adresse_mere' => $parent->adresse_mere ?? 'N/A',
        ]);
    }

    public function import(Request $request)
    {
        $this->ensureSession(['admin', 'enseignant']);

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ]);

        $sheet = IOFactory::load($request->file('file')->getRealPath())->getActiveSheet();
        $rows = $sheet->toArray();
        $inserted = 0;
        $ignored = 0;

        DB::transaction(function () use ($rows, &$inserted, &$ignored) {
            for ($i = 1; $i < count($rows); $i++) {
                $data = $this->importRowToStudentData($rows[$i] ?? []);

                if (! $data || ! DB::table('classes')->where('id', $data['classe_id'])->exists()) {
                    $ignored++;
                    continue;
                }

                $data['matricule'] = $this->generateMatricule($data['annee_scolaire']);
                $data['photo'] = 'uploads/eleves/default.jpg';

                DB::table('eleves')->insert($this->studentPayload($data));
                $this->upsertParents($data);
                $inserted++;
            }
        });

        return response()->json([
            'status' => $inserted > 0 ? 'success' : 'warning',
            'message' => "Importation terminee : {$inserted} eleve(s) ajoute(s), {$ignored} ligne(s) ignoree(s).",
        ]);
    }

    public function template()
    {
        $this->ensureSession(['admin', 'enseignant', 'parent', 'staff']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = [
            'Prenom',
            'Nom',
            'Date de naissance (YYYY-MM-DD)',
            'Lieu de naissance',
            'Nom du pere',
            'Nom de la mere',
            'Telephone',
            'Adresse',
            'N Acte',
            'Fonkotany',
            'Commune',
            'Ecole precedente',
            'Classe (ID ou nom exact)',
            'Annee scolaire (YYYY-YYYY)',
            'Distance domicile > 5 km (0 ou 1)',
            'Genre (G ou F)',
            'Statut (nouveau, passant, redoublant)',
            'En situation de handicap (0 ou 1)',
            'Telephone du pere',
            'Profession du pere',
            'Adresse du pere',
            'Telephone de la mere',
            'Profession de la mere',
            'Adresse de la mere',
            'Creer compte parent (0 ou 1)',
            'Responsable compte parent (pere, mere, tuteur, parent)',
            'Nom compte parent',
            'Email compte parent',
            'Mot de passe parent',
        ];
        $sample = [[
            'Jean',
            'Dupont',
            '2010-05-15',
            'Antananarivo',
            'Paul Dupont',
            'Marie Dupont',
            '0123456789',
            'Lot 123',
            'ACT123',
            'Ambohijanahary',
            'Antananarivo',
            'Lycee Moderne',
            '6e A',
            $this->currentSchoolYear(),
            1,
            'G',
            'nouveau',
            0,
            '0987654321',
            'Medecin',
            'Lot 456',
            '0876543210',
            'Enseignante',
            'Lot 789',
            1,
            'pere',
            'Paul Dupont',
            'parent@example.com',
            '123456789',
        ]];

        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray($sample, null, 'A2');
        $sheet->getStyle('A1:AC1')->getFont()->setBold(true);
        $sheet->getStyle('A1:AC1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        foreach (range('A', 'Z') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        foreach (['AA', 'AB', 'AC'] as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = storage_path('app/modele_importation_eleves.xlsx');

        if (! is_dir(dirname($filename))) {
            mkdir(dirname($filename), 0777, true);
        }

        $writer->save($filename);

        return response()->download($filename, 'modele_importation_eleves.xlsx')->deleteFileAfterSend(true);
    }

    private function studentsQuery(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $classeId = (int) $request->query('classe', 0);
        $annee = trim((string) $request->query('annee', $this->currentSchoolYear()));

        $query = DB::table('eleves as e')
            ->join('classes as c', 'e.id_classe', '=', 'c.id')
            ->select('e.*', 'c.nom as classe')
            ->orderBy('c.niveau')
            ->orderBy('e.nom');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('e.nom', 'like', "%{$search}%")
                    ->orWhere('e.prenom', 'like', "%{$search}%")
                    ->orWhere('e.matricule', 'like', "%{$search}%");
            });
        }

        if ($classeId > 0) {
            $query->where('e.id_classe', $classeId);
        }

        if ($annee !== '') {
            $query->where('e.annee_scolaire', $annee);
        }

        return $query;
    }

    private function validatedStudentData(Request $request, bool $updating = false): array
    {
        $validated = $request->validate([
            'prenom' => ['required', 'string', 'max:100'],
            'nom' => ['required', 'string', 'max:100'],
            'date_naissance' => ['required', 'date'],
            'lieu_naissance' => ['required', 'string', 'max:150'],
            'adresse' => ['required', 'string', 'max:255'],
            'numero_acte' => ['required', 'string', 'max:100'],
            'fonkotany' => ['required', 'string', 'max:150'],
            'commune' => ['required', 'string', 'max:150'],
            'ecole_ancienne' => ['nullable', 'string', 'max:150'],
            'nom_pere' => ['nullable', 'string', 'max:150'],
            'nom_mere' => ['nullable', 'string', 'max:150'],
            'telephone' => ['required', 'string', 'max:50'],
            'telephone_pere' => ['nullable', 'string', 'max:50'],
            'telephone_mere' => ['nullable', 'string', 'max:50'],
            'profession_pere' => ['nullable', 'string', 'max:150'],
            'profession_mere' => ['nullable', 'string', 'max:150'],
            'adresse_pere' => ['nullable', 'string', 'max:255'],
            'adresse_mere' => ['nullable', 'string', 'max:255'],
            'creer_compte_parent' => ['nullable'],
            'parent_lien' => ['nullable', 'in:pere,mere,tuteur,parent'],
            'parent_nom_compte' => ['nullable', 'string', 'max:150'],
            'parent_email_compte' => ['nullable', 'email', 'max:100'],
            'parent_mot_de_passe' => ['nullable', 'string', 'min:6', 'max:100'],
            'classe_id' => ['required', 'integer', 'exists:classes,id'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'genre' => ['required', 'in:F,G'],
            'statut' => ['required', 'in:nouveau,passant,redoublant'],
            'photo' => [$updating ? 'nullable' : 'nullable', 'image', 'max:4096'],
        ]);

        $validated['distance_domicile'] = $request->boolean('distance_domicile') ? 1 : 0;
        $validated['est_handicap'] = $request->boolean('est_handicap') ? 1 : 0;
        $validated['creer_compte_parent'] = $request->boolean('creer_compte_parent') ? 1 : 0;

        return $validated;
    }

    private function importRowToStudentData(array $row): ?array
    {
        if (count($row) < 24) {
            return null;
        }

        $classeValue = trim((string) ($row[12] ?? ''));
        $classeId = 0;
        if (is_numeric($classeValue)) {
            $classeId = (int) $classeValue;
        } elseif ($classeValue !== '') {
            $classeId = (int) (DB::table('classes')->where('nom', $classeValue)->value('id') ?? 0);
        }

        $data = [
            'prenom' => trim((string) ($row[0] ?? '')),
            'nom' => trim((string) ($row[1] ?? '')),
            'date_naissance' => $this->normalizeExcelDate($row[2] ?? ''),
            'lieu_naissance' => trim((string) ($row[3] ?? '')),
            'nom_pere' => trim((string) ($row[4] ?? '')),
            'nom_mere' => trim((string) ($row[5] ?? '')),
            'telephone' => trim((string) ($row[6] ?? '')),
            'adresse' => trim((string) ($row[7] ?? '')),
            'numero_acte' => trim((string) ($row[8] ?? '')),
            'fonkotany' => trim((string) ($row[9] ?? '')),
            'commune' => trim((string) ($row[10] ?? '')),
            'ecole_ancienne' => trim((string) ($row[11] ?? '')),
            'classe_id' => $classeId,
            'annee_scolaire' => trim((string) ($row[13] ?? '')),
            'distance_domicile' => (int) ($row[14] ?? 0),
            'genre' => strtoupper(trim((string) ($row[15] ?? ''))),
            'statut' => strtolower(trim((string) ($row[16] ?? ''))),
            'est_handicap' => (int) ($row[17] ?? 0),
            'telephone_pere' => trim((string) ($row[18] ?? '')),
            'profession_pere' => trim((string) ($row[19] ?? '')),
            'adresse_pere' => trim((string) ($row[20] ?? '')),
            'telephone_mere' => trim((string) ($row[21] ?? '')),
            'profession_mere' => trim((string) ($row[22] ?? '')),
            'adresse_mere' => trim((string) ($row[23] ?? '')),
            'creer_compte_parent' => (int) ($row[24] ?? 0),
            'parent_lien' => in_array(trim((string) ($row[25] ?? '')), ['pere', 'mere', 'tuteur', 'parent'], true) ? trim((string) ($row[25] ?? '')) : 'pere',
            'parent_nom_compte' => trim((string) ($row[26] ?? '')),
            'parent_email_compte' => trim((string) ($row[27] ?? '')),
            'parent_mot_de_passe' => trim((string) ($row[28] ?? '')),
        ];

        $required = ['prenom', 'nom', 'date_naissance', 'lieu_naissance', 'telephone', 'adresse', 'numero_acte', 'fonkotany', 'commune', 'annee_scolaire'];

        foreach ($required as $field) {
            if ($data[$field] === '') {
                return null;
            }
        }

        if ($data['classe_id'] <= 0 || ! in_array($data['genre'], ['G', 'F'], true) || ! in_array($data['statut'], ['nouveau', 'passant', 'redoublant'], true)) {
            return null;
        }

        return $data;
    }

    private function normalizeExcelDate(mixed $value): string
    {
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }

        return trim((string) $value);
    }

    private function studentPayload(array $data): array
    {
        return [
            'matricule' => $data['matricule'],
            'nom' => trim($data['nom']),
            'prenom' => trim($data['prenom']),
            'date_naissance' => $data['date_naissance'],
            'lieu_naissance' => trim($data['lieu_naissance']),
            'telephone' => trim($data['telephone']),
            'adresse' => trim($data['adresse']),
            'numero_acte' => trim($data['numero_acte']),
            'fonkotany' => trim($data['fonkotany']),
            'commune' => trim($data['commune']),
            'ecole_ancienne' => trim((string) ($data['ecole_ancienne'] ?? '')),
            'id_classe' => (int) $data['classe_id'],
            'photo' => $data['photo'],
            'annee_scolaire' => trim($data['annee_scolaire']),
            'nom_pere' => trim((string) ($data['nom_pere'] ?? '')),
            'nom_mere' => trim((string) ($data['nom_mere'] ?? '')),
            'distance_domicile' => (int) $data['distance_domicile'],
            'genre' => $data['genre'],
            'statut' => $data['statut'],
            'est_handicap' => (int) $data['est_handicap'],
        ];
    }

    private function upsertParents(array $data): void
    {
        $nomPere = trim((string) ($data['nom_pere'] ?? ''));
        $nomMere = trim((string) ($data['nom_mere'] ?? ''));
        $annee = trim((string) $data['annee_scolaire']);

        if ($nomPere === '' && $nomMere === '') {
            return;
        }

        $payload = [
            'telephone_pere' => trim((string) ($data['telephone_pere'] ?? '')),
            'profession_pere' => trim((string) ($data['profession_pere'] ?? '')),
            'adresse_pere' => trim((string) ($data['adresse_pere'] ?? '')),
            'telephone_mere' => trim((string) ($data['telephone_mere'] ?? '')),
            'profession_mere' => trim((string) ($data['profession_mere'] ?? '')),
            'adresse_mere' => trim((string) ($data['adresse_mere'] ?? '')),
            'telephone' => trim((string) ($data['telephone'] ?? '')),
        ];

        $exists = DB::table('parents')
            ->where('nom_pere', $nomPere)
            ->where('nom_mere', $nomMere)
            ->where('annee_scolaire', $annee)
            ->exists();

        if ($exists) {
            DB::table('parents')
                ->where('nom_pere', $nomPere)
                ->where('nom_mere', $nomMere)
                ->where('annee_scolaire', $annee)
                ->update($payload);

            return;
        }

        DB::table('parents')->insert($payload + [
            'nom' => $nomPere !== '' ? $nomPere : $nomMere,
            'prenom' => $nomPere !== '' ? $nomMere : '',
            'lien' => $nomPere !== '' ? 'pere' : 'mere',
            'nom_pere' => $nomPere,
            'nom_mere' => $nomMere,
            'annee_scolaire' => $annee,
            'created_at' => now(),
        ]);
    }

    private function syncParentAccount(int $studentId, array $data): void
    {
        if ((int) ($data['creer_compte_parent'] ?? 0) !== 1) {
            return;
        }

        $email = strtolower(trim((string) ($data['parent_email_compte'] ?? '')));
        if ($email === '') {
            abort(422, 'Email du compte parent requis.');
        }

        $link = (string) ($data['parent_lien'] ?? 'parent');
        $name = trim((string) ($data['parent_nom_compte'] ?? ''));
        $phone = '';

        if ($name === '') {
            if ($link === 'pere') {
                $name = trim((string) ($data['nom_pere'] ?? ''));
                $phone = trim((string) ($data['telephone_pere'] ?? ''));
            } elseif ($link === 'mere') {
                $name = trim((string) ($data['nom_mere'] ?? ''));
                $phone = trim((string) ($data['telephone_mere'] ?? ''));
            }
        }

        if ($name === '') {
            $name = 'Parent de '.trim(($data['prenom'] ?? '').' '.($data['nom'] ?? ''));
        }

        if ($phone === '') {
            $phone = trim((string) ($data['telephone_pere'] ?? $data['telephone_mere'] ?? $data['telephone'] ?? ''));
        }

        $user = DB::table('utilisateurs')->where('email', $email)->first();
        if ($user && $user->role !== 'parent') {
            abort(422, 'Cet email appartient deja a un compte '.$user->role.'.');
        }

        $payload = [
            'nom' => $name,
            'email' => $email,
            'role' => 'parent',
            'avatar' => 'images/default-avatar.png',
        ];

        $password = trim((string) ($data['parent_mot_de_passe'] ?? ''));
        if (! $user && $password === '') {
            abort(422, 'Mot de passe du compte parent requis.');
        }
        if ($password !== '') {
            $payload['mot_de_passe'] = Hash::make($password);
        }

        if ($user) {
            DB::table('utilisateurs')->where('id', $user->id)->update($payload);
            $userId = (int) $user->id;
        } else {
            $userId = DB::table('utilisateurs')->insertGetId($payload + ['cree_le' => now()]);
        }

        $this->ensureParentDefaultPermissions($userId);

        DB::table('parent_eleves')->updateOrInsert(
            ['parent_user_id' => $userId, 'eleve_id' => $studentId],
            [
                'lien' => $link ?: 'parent',
                'nom_contact' => $name,
                'telephone' => $phone,
                'principal' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    private function ensureParentDefaultPermissions(int $userId): void
    {
        $defaults = [
            'emploi_temps' => 'lecture',
            'bulletin' => 'lecture',
            'chat_private' => 'lecture',
            'chat_group' => 'lecture',
        ];

        foreach (array_keys(config('novaskol.modules')) as $module) {
            DB::table('permissions')->updateOrInsert(
                ['utilisateur_id' => $userId, 'module' => $module],
                [
                    'role' => 'parent',
                    'acces' => $defaults[$module] ?? 'masquer',
                ]
            );
        }
    }

    private function generateMatricule(string $anneeScolaire): string
    {
        $prefix = substr($anneeScolaire, 0, 4);
        $count = DB::table('eleves')->where('annee_scolaire', $anneeScolaire)->count() + 1;

        return $prefix.sprintf('%04d', $count);
    }

    private function storePhoto(Request $request): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        $file = $request->file('photo');
        $name = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = "{$name}.{$extension}";
        $destination = public_path('legacy/uploads/eleves');

        if (! is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $file->move($destination, $filename);

        return "uploads/eleves/{$filename}";
    }

    private function deletePhoto(?string $photo): void
    {
        if (! $photo || str_contains($photo, 'default.jpg')) {
            return;
        }

        $path = public_path('legacy/'.ltrim(str_replace(['Uploads/', 'uploads/'], 'uploads/', $photo), '/'));

        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function renderGrid($students): string
    {
        if ($students->isEmpty()) {
            return '<div class="empty-state">Aucun eleve trouve pour ce filtre.</div>';
        }

        return $students->map(function ($student) {
            $age = $this->age($student->date_naissance);
            $distance = $student->distance_domicile ? 'Oui' : 'Non';
            $genre = $student->genre === 'F' ? 'Fille' : 'Garcon';
            $statut = ucfirst((string) $student->statut);
            $handicap = $student->est_handicap ? 'Oui' : 'Non';
            $photo = e($this->photoUrl($student->photo));
            $data = $this->studentDataAttributes($student);

            return <<<HTML
<div class="card {$student->statut}">
    <div class="card-buttons">
        <button class="btn-modifier" {$data}><i class="fa fa-edit"></i> Modifier</button>
        <button class="btn-supprimer" data-id="{$student->id}"><i class="fa fa-trash"></i> Supprimer</button>
        <button class="btn-details" {$data}><i class="fa fa-file-text"></i> Details</button>
        <button class="btn-parents" data-nom_pere="{$this->attr($student->nom_pere)}" data-nom_mere="{$this->attr($student->nom_mere)}" data-annee_scolaire="{$this->attr($student->annee_scolaire)}"><i class="fa fa-users"></i> Parents</button>
    </div>
    <img src="{$photo}" alt="Photo eleve">
    <div><i class="fa fa-id-card"></i><strong>Matricule:</strong> {$this->text($student->matricule)}</div>
    <div><i class="fa fa-user"></i><strong>Nom:</strong> {$this->text(trim($student->prenom.' '.$student->nom))}</div>
    <div><i class="fa fa-birthday-cake"></i><strong>Ne le:</strong> {$this->text($student->date_naissance)} ({$age} ans)</div>
    <div><i class="fa fa-map-marker"></i><strong>Lieu:</strong> {$this->text($student->lieu_naissance ?: 'N/A')}</div>
    <div><i class="fa fa-male"></i><strong>Pere:</strong> {$this->text($student->nom_pere ?: 'N/A')}</div>
    <div><i class="fa fa-female"></i><strong>Mere:</strong> {$this->text($student->nom_mere ?: 'N/A')}</div>
    <div><i class="fa fa-phone"></i><strong>Contact:</strong> {$this->text($student->telephone)}</div>
    <div><i class="fa fa-home"></i><strong>Adresse:</strong> {$this->text($student->adresse ?: 'N/A')}</div>
    <div><i class="fa fa-road"></i><strong>Distance > 5 km:</strong> {$distance}</div>
    <div><i class="fa fa-venus-mars"></i><strong>Genre:</strong> {$genre}</div>
    <div><i class="fa fa-graduation-cap"></i><strong>Statut:</strong> <span class="status-badge {$student->statut}">{$this->text($statut)}</span></div>
    <div><i class="fa fa-wheelchair"></i><strong>Handicap:</strong> {$handicap}</div>
    <div><i class="fa fa-university"></i><strong>Classe:</strong> {$this->text($student->classe)}</div>
    <div><i class="fa fa-calendar"></i><strong>Annee:</strong> {$this->text($student->annee_scolaire)}</div>
</div>
HTML;
        })->implode('');
    }

    private function renderPrintRows($students): string
    {
        return $students->map(function ($student) {
            $age = $this->age($student->date_naissance);
            $distance = $student->distance_domicile ? 'Oui' : 'Non';
            $genre = $student->genre === 'F' ? 'Fille' : 'Garcon';
            $statut = ucfirst((string) $student->statut);
            $handicap = $student->est_handicap ? 'Oui' : 'Non';
            $photo = e($this->photoUrl($student->photo));

            return '<tr>'
                ."<td><img src='{$photo}' class='photo-eleve' alt='Photo eleve'></td>"
                .'<td>'.$this->text($student->matricule).'</td>'
                .'<td>'.$this->text($student->prenom).'</td>'
                .'<td>'.$this->text($student->nom).'</td>'
                .'<td>'.$this->text($student->date_naissance).'</td>'
                .'<td>'.$age.' ans</td>'
                .'<td>'.$this->text($student->lieu_naissance ?: 'N/A').'</td>'
                .'<td>'.$this->text($student->nom_pere ?: 'N/A').'</td>'
                .'<td>'.$this->text($student->nom_mere ?: 'N/A').'</td>'
                .'<td>'.$this->text($student->telephone).'</td>'
                .'<td>'.$this->text($student->adresse ?: 'N/A').'</td>'
                .'<td>'.$this->text($student->numero_acte ?: 'N/A').'</td>'
                .'<td>'.$this->text($student->fonkotany ?: 'N/A').'</td>'
                .'<td>'.$this->text($student->commune ?: 'N/A').'</td>'
                .'<td>'.$this->text($student->ecole_ancienne ?: 'N/A').'</td>'
                .'<td>'.$distance.'</td>'
                .'<td>'.$genre.'</td>'
                .'<td>'.$this->text($statut).'</td>'
                .'<td>'.$handicap.'</td>'
                .'<td>'.$this->text($student->classe).'</td>'
                .'<td>'.$this->text($student->annee_scolaire).'</td>'
                .'</tr>';
        })->implode('');
    }

    private function studentDataAttributes(object $student): string
    {
        $fields = [
            'id' => $student->id,
            'prenom' => $student->prenom,
            'nom' => $student->nom,
            'matricule' => $student->matricule,
            'date_naissance' => $student->date_naissance,
            'lieu_naissance' => $student->lieu_naissance,
            'nom_pere' => $student->nom_pere,
            'nom_mere' => $student->nom_mere,
            'telephone' => $student->telephone,
            'adresse' => $student->adresse,
            'numero_acte' => $student->numero_acte,
            'fonkotany' => $student->fonkotany,
            'commune' => $student->commune,
            'ecole_ancienne' => $student->ecole_ancienne,
            'classe' => $student->id_classe,
            'classe_nom' => $student->classe,
            'annee' => $student->annee_scolaire,
            'distance' => $student->distance_domicile,
            'genre' => $student->genre,
            'statut' => $student->statut,
            'handicap' => $student->est_handicap,
            'photo' => $this->photoUrl($student->photo),
        ];

        return collect($fields)
            ->map(fn ($value, $key) => 'data-'.$key.'="'.$this->attr($value).'"')
            ->implode(' ');
    }

    private function photoUrl(?string $photo): string
    {
        $photo = $photo ?: 'uploads/eleves/default.jpg';
        $photo = ltrim(str_replace(['Uploads/', 'uploads/'], 'uploads/', $photo), '/');

        return asset('legacy/'.$photo);
    }

    private function age(?string $date): int
    {
        if (! $date) {
            return 0;
        }

        return \Carbon\Carbon::parse($date)->age;
    }

    private function text(mixed $value): string
    {
        return e((string) $value);
    }

    private function attr(mixed $value): string
    {
        return e((string) $value, false);
    }

    private function currentSchoolYear(): string
    {
        $latest = DB::table('eleves')
            ->whereNotNull('annee_scolaire')
            ->where('annee_scolaire', '!=', '')
            ->orderByDesc('annee_scolaire')
            ->value('annee_scolaire');

        if ($latest) {
            return (string) $latest;
        }

        $year = (int) now()->format('Y');

        return $year.'-'.($year + 1);
    }

    private function userPermissions(): array
    {
        if (! session('utilisateur.id')) {
            return [];
        }

        return DB::table('permissions')
            ->where('utilisateur_id', session('utilisateur.id'))
            ->pluck('acces', 'module')
            ->all();
    }

    private function ensureSession(array $roles = ['admin', 'enseignant', 'parent', 'staff']): void
    {
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), $roles, true), 403);
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) [
            'nom' => 'Ecole',
            'logo' => 'novaskol.png',
        ];
    }
}
