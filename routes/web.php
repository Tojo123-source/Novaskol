<?php

use App\Http\Controllers\Auth\LegacyAuthController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Connected\ConnectedInitController;
use App\Http\Controllers\Connected\ConnectedSyncController;
use App\Http\Controllers\ConnectedDeviceController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ExamBlankController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\HumanResourceController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\PedagogyController;
use App\Http\Controllers\ParentPortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SystemDiagnosticController;
use App\Http\Controllers\TeacherWorkspaceController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Route;

Route::view('/mentions-legales', 'auth.mentions-legales')->name('public.mentions-legales');
Route::view('/confidentialite', 'auth.confidentialite')->name('public.confidentialite');
Route::view('/cgu', 'auth.cgu')->name('public.cgu');
Route::view('/cookies', 'auth.cookies')->name('public.cookies');

Route::get('/installation', [InstallationController::class, 'show'])->name('installation.show');
Route::post('/installation', [InstallationController::class, 'store'])->name('installation.store');
Route::post('/installation/reset-demo', [InstallationController::class, 'resetDemo'])->name('installation.reset-demo');
Route::get('/appareil-connecte', [ConnectedDeviceController::class, 'show'])->name('connected.setup');
Route::get('/connecte', [ConnectedDeviceController::class, 'app'])->name('connected.app');
Route::get('/connecte/{path}', [ConnectedDeviceController::class, 'app'])->where('path', '.*')->name('connected.app.asset');

Route::get('/', [LegacyAuthController::class, 'show'])->name('login');
Route::post('/login', [LegacyAuthController::class, 'login'])->name('login.attempt');
Route::post('/register', [LegacyAuthController::class, 'register'])->name('register.attempt');
Route::post('/logout', [LegacyAuthController::class, 'logout'])->name('logout');
Route::post('/profil', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profil/password', [ProfileController::class, 'password'])->name('profile.password');
Route::get('/reseau-local/ping', fn () => response()->json([
    'success' => true,
    'service' => 'novaskol-principal',
    'time' => now()->toDateTimeString(),
]))->name('modules.reseau-local.ping');
Route::get('/reseau-local/manifest-appareil', [SettingsController::class, 'connectedDeviceManifest'])->name('modules.reseau-local.device.manifest');
Route::post('/reseau-local/appareil-connecte', [SettingsController::class, 'registerLocalDevice'])->name('modules.reseau-local.device.register');
Route::post('/reseau-local/appairer-appareil', [SettingsController::class, 'pairConnectedDevice'])->name('modules.reseau-local.device.pair');
Route::post('/reseau-local/bootstrap-appareil', [SettingsController::class, 'connectedDeviceBootstrap'])->name('modules.reseau-local.device.bootstrap');
Route::post('/reseau-local/recevoir-lot', [SettingsController::class, 'receiveConnectedDeviceBatch'])->name('modules.reseau-local.device.batch');
Route::options('/reseau-local/{any}', fn () => response()->noContent())->where('any', '.*');
Route::get('/connected/init', ConnectedInitController::class)->name('connected.init');
Route::get('/connected/sync/status', [ConnectedSyncController::class, 'status'])->name('connected.sync.status');
Route::get('/connected/sync/run', [ConnectedSyncController::class, 'run'])->name('connected.sync.run');
Route::post('/connected/disconnect', [ConnectedInitController::class, 'disconnect'])->name('connected.disconnect');
Route::post('/connected/switch-user', [ConnectedInitController::class, 'switchUser'])->name('connected.switch-user');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::post('/contact/pending', [ContactController::class, 'sendPending'])->name('contact.pending');

Route::middleware('module.access:dashboard')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/dashboard/events', [DashboardController::class, 'events'])->name('dashboard.events');
    Route::post('/dashboard/events', [DashboardController::class, 'storeEvent'])->name('dashboard.events.store');
});
Route::get('/mon-espace', RoleDashboardController::class)->name('role.dashboard');
Route::get('/parent/espace', ParentPortalController::class)->name('parent.portal');
Route::get('/parent/responsable-chat', [CommunicationController::class, 'parentResponsibleChat'])->name('parent.chat');
Route::get('/enseignant/espace', [TeacherWorkspaceController::class, 'index'])->name('teacher.workspace');
Route::post('/enseignant/lecons', [TeacherWorkspaceController::class, 'storeLesson'])->name('teacher.lessons.store');
Route::put('/enseignant/lecons/{id}', [TeacherWorkspaceController::class, 'updateLesson'])->name('teacher.lessons.update');
Route::delete('/enseignant/lecons/{id}', [TeacherWorkspaceController::class, 'deleteLesson'])->name('teacher.lessons.delete');
Route::post('/enseignant/taches', [TeacherWorkspaceController::class, 'storeTask'])->name('teacher.tasks.store');
Route::post('/enseignant/taches/{id}/toggle', [TeacherWorkspaceController::class, 'toggleTask'])->name('teacher.tasks.toggle');
Route::post('/dashboard/notifications/{id}/read', [DashboardController::class, 'markNotificationRead'])->name('dashboard.notifications.read');
Route::delete('/dashboard/notifications/{id}', [DashboardController::class, 'deleteNotification'])->name('dashboard.notifications.delete');
Route::post('/dashboard/notifications/read-all', [DashboardController::class, 'markAllNotificationsRead'])->name('dashboard.notifications.read-all');
Route::get('/dashboard/notifications/poll', [DashboardController::class, 'pollNotifications'])->name('dashboard.notifications.poll');

Route::middleware('module.access:ecole')->group(function () {
    Route::get('/ecole', [SchoolController::class, 'show'])->name('modules.ecole');
    Route::post('/ecole/update', [SchoolController::class, 'update'])->name('modules.ecole.update');
});

Route::middleware('module.access:inscription')->group(function () {
    Route::get('/inscription', [StudentController::class, 'index'])->name('modules.inscription');
    Route::get('/inscription/search', [StudentController::class, 'search'])->name('modules.inscription.search');
    Route::post('/inscription', [StudentController::class, 'store'])->name('modules.inscription.store');
    Route::post('/inscription/import', [StudentController::class, 'import'])->name('modules.inscription.import');
    Route::get('/inscription/import/template', [StudentController::class, 'template'])->name('modules.inscription.template');
    Route::post('/inscription/{id}', [StudentController::class, 'update'])->name('modules.inscription.update');
    Route::delete('/inscription/{id}', [StudentController::class, 'destroy'])->name('modules.inscription.delete');
    Route::get('/inscription/parents/details', [StudentController::class, 'parents'])->name('modules.inscription.parents');
});

Route::middleware('module.access:matieres')->group(function () {
    Route::get('/matieres', [SubjectController::class, 'show'])->name('modules.matieres');
    Route::post('/matieres', [SubjectController::class, 'store'])->name('modules.matieres.store');
    Route::post('/matieres/rename', [SubjectController::class, 'rename'])->name('modules.matieres.rename');
    Route::post('/matieres/delete', [SubjectController::class, 'destroy'])->name('modules.matieres.delete');
    Route::post('/matieres/assignments', [SubjectController::class, 'updateAssignments'])->name('modules.matieres.assignments');
});

Route::middleware('module.access:notes')->group(function () {
    Route::get('/notes', [GradeController::class, 'index'])->name('modules.notes');
    Route::post('/notes', [GradeController::class, 'store'])->name('modules.notes.store');
});

Route::middleware('module.access:bulletin')->group(function () {
    Route::get('/bulletin', [BulletinController::class, 'index'])->name('modules.bulletin');
    Route::get('/bulletin/search', [BulletinController::class, 'search'])->name('modules.bulletin.search');
    Route::get('/bulletin/eleve', [BulletinController::class, 'student'])->name('modules.bulletin.student');
    Route::get('/bulletin/classe', [BulletinController::class, 'classe'])->name('modules.bulletin.classe');
    Route::get('/bulletin/annuel', [BulletinController::class, 'annualIndex'])->name('modules.bulletin.annual');
    Route::get('/bulletin/annuel/eleve', [BulletinController::class, 'annualStudent'])->name('modules.bulletin.annual.student');
});

Route::get('/resultats', [ResultController::class, 'index'])->middleware('module.access:resultats')->name('modules.resultats');

Route::middleware('module.access:examen_blanc')->group(function () {
    Route::get('/examen-blanc', [ExamBlankController::class, 'index'])->name('modules.examen-blanc');
    Route::post('/examen-blanc', [ExamBlankController::class, 'store'])->name('modules.examen-blanc.store');
});
Route::get('/resultats-examen-blanc', [ExamBlankController::class, 'results'])->middleware('module.access:resultats_examen_blanc')->name('modules.resultats-examen-blanc');

Route::middleware('module.access:emploi_temps')->group(function () {
    Route::get('/emploi-du-temps', [ScheduleController::class, 'index'])->name('modules.emploi-temps');
    Route::post('/emploi-du-temps', [ScheduleController::class, 'store'])->name('modules.emploi-temps.store');
});
Route::middleware('module.access:fiche_presence')->group(function () {
    Route::match(['get', 'post'], '/presence-etudiant', [PedagogyController::class, 'presence'])->name('modules.presence-etudiant');
    Route::post('/presence-etudiant/numerique', [PedagogyController::class, 'storeStudentPresence'])->name('modules.presence-etudiant.store');
});
Route::middleware('module.access:calendrier')->group(function () {
    Route::get('/calendrier', [PedagogyController::class, 'calendar'])->name('modules.calendrier');
    Route::get('/calendrier/events', [PedagogyController::class, 'calendarEvents'])->name('modules.calendrier.events');
    Route::post('/calendrier/events', [PedagogyController::class, 'storeCalendarEvent'])->name('modules.calendrier.events.store');
});
Route::middleware('module.access:notifications')->group(function () {
    Route::get('/notifications', [PedagogyController::class, 'notifications'])->name('modules.notifications');
    Route::post('/notifications', [PedagogyController::class, 'storeNotification'])->name('modules.notifications.store');
    Route::delete('/notifications', [PedagogyController::class, 'deleteAllNotifications'])->name('modules.notifications.delete-all');
    Route::delete('/notifications/{id}', [PedagogyController::class, 'deleteNotification'])->name('modules.notifications.delete');
});
Route::match(['get', 'post'], '/cartes', [PedagogyController::class, 'cards'])->middleware('module.access:cartes')->name('modules.cartes');
Route::get('/cartes/connecte', [PedagogyController::class, 'cardsConnecte'])->middleware('module.access:cartes')->name('modules.cartes.connecte');
Route::get('/qr-code/{token}', [PedagogyController::class, 'qrCodeImage'])->name('modules.qrcode.image');
Route::post('/qr-presence/{token}', [PedagogyController::class, 'qrPresenceScan'])->name('modules.qrcode.presence-scan');
Route::middleware('module.access:depot_dossier')->group(function () {
    Route::get('/depot-dossier', [PedagogyController::class, 'documents'])->name('modules.depot-dossier');
    Route::post('/depot-dossier', [PedagogyController::class, 'storeDocument'])->name('modules.depot-dossier.store');
    Route::delete('/depot-dossier/{id}', [PedagogyController::class, 'deleteDocument'])->name('modules.depot-dossier.delete');
});
Route::get('/fpe', [PedagogyController::class, 'fpe'])->middleware('module.access:fpe')->name('modules.fpe');
Route::get('/liste-assurance', [PedagogyController::class, 'assurance'])->middleware('module.access:liste_assurance')->name('modules.liste-assurance');

Route::middleware('module.access:liste_classes')->group(function () {
    Route::get('/classes', [ClassroomController::class, 'index'])->name('modules.liste-classes');
    Route::post('/classes', [ClassroomController::class, 'store'])->name('modules.liste-classes.store');
    Route::put('/classes/{id}', [ClassroomController::class, 'update'])->name('modules.liste-classes.update');
    Route::delete('/classes/{id}', [ClassroomController::class, 'destroy'])->name('modules.liste-classes.delete');
    Route::post('/classes/{id}/students', [ClassroomController::class, 'students'])->name('modules.liste-classes.students');
});

Route::middleware('module.access:enseignants')->group(function () {
    Route::get('/enseignants', [HumanResourceController::class, 'teachers'])->name('modules.enseignants');
    Route::post('/enseignants', [HumanResourceController::class, 'storeTeacher'])->name('modules.enseignants.store');
    Route::put('/enseignants/{id}', [HumanResourceController::class, 'updateTeacher'])->name('modules.enseignants.update');
    Route::delete('/enseignants/{id}', [HumanResourceController::class, 'deleteTeacher'])->name('modules.enseignants.delete');
});

Route::middleware('module.access:staff')->group(function () {
    Route::get('/staff', [HumanResourceController::class, 'staff'])->name('modules.staff');
    Route::post('/staff', [HumanResourceController::class, 'storeStaff'])->name('modules.staff.store');
    Route::put('/staff/{id}', [HumanResourceController::class, 'updateStaff'])->name('modules.staff.update');
    Route::delete('/staff/{id}', [HumanResourceController::class, 'deleteStaff'])->name('modules.staff.delete');
});

Route::middleware('module.access:pointage')->group(function () {
    Route::get('/pointage', [HumanResourceController::class, 'unifiedPresence'])->name('modules.pointage');
});

Route::middleware('module.access:presence')->group(function () {
    Route::get('/presence', [HumanResourceController::class, 'teacherPresence'])->name('modules.presence');
    Route::post('/presence', [HumanResourceController::class, 'storeTeacherPresence'])->name('modules.presence.store');
    Route::put('/presence/{id}', [HumanResourceController::class, 'updateTeacherPresence'])->name('modules.presence.update');
    Route::delete('/presence/{id}', [HumanResourceController::class, 'deleteTeacherPresence'])->name('modules.presence.delete');
});

Route::middleware('module.access:presence_staff')->group(function () {
    Route::get('/presence-staff', [HumanResourceController::class, 'staffPresence'])->name('modules.presence-staff');
    Route::post('/presence-staff', [HumanResourceController::class, 'storeStaffPresence'])->name('modules.presence-staff.store');
    Route::put('/presence-staff/{id}', [HumanResourceController::class, 'updateStaffPresence'])->name('modules.presence-staff.update');
    Route::delete('/presence-staff/{id}', [HumanResourceController::class, 'deleteStaffPresence'])->name('modules.presence-staff.delete');
});

Route::middleware('module.access:permissions')->group(function () {
    Route::get('/permissions', [HumanResourceController::class, 'permissions'])->name('modules.permissions');
    Route::post('/permissions', [HumanResourceController::class, 'updatePermissions'])->name('modules.permissions.update');
});

Route::middleware('module.access:gestion_ressource')->group(function () {
    Route::get('/gestion-ressource', [HumanResourceController::class, 'resources'])->name('modules.gestion-ressource');
    Route::post('/gestion-ressource', [HumanResourceController::class, 'resourceAction'])->name('modules.gestion-ressource.action');
});

Route::get('/communication', [CommunicationController::class, 'index'])->middleware('module.access:communication')->name('modules.communication');
Route::middleware('module.access:chat_private')->group(function () {
    Route::get('/chat-prive', [CommunicationController::class, 'privateChat'])->name('modules.chat-prive');
    Route::get('/chat-prive/contacts', [CommunicationController::class, 'privateContacts'])->name('modules.chat-prive.contacts');
    Route::post('/chat-prive/conversation', [CommunicationController::class, 'privateConversation'])->name('modules.chat-prive.conversation');
});
Route::middleware('module.access:chat_group')->group(function () {
    Route::get('/chat-groupe', [CommunicationController::class, 'groupChat'])->name('modules.chat-groupe');
    Route::post('/chat-groupe', [CommunicationController::class, 'storeGroup'])->name('modules.chat-groupe.store');
    Route::put('/chat-groupe/{groupId}', [CommunicationController::class, 'updateGroup'])->name('modules.chat-groupe.update');
    Route::delete('/chat-groupe/{groupId}', [CommunicationController::class, 'deleteGroup'])->name('modules.chat-groupe.delete');
});
Route::get('/chat/messages/{conversationId}', [CommunicationController::class, 'messages'])->name('modules.chat.messages');
Route::post('/chat/messages/{conversationId}', [CommunicationController::class, 'sendMessage'])->name('modules.chat.messages.send');
Route::post('/chat/typing/{conversationId}', [CommunicationController::class, 'typing'])->name('modules.chat.typing');
Route::get('/chat/unread', [CommunicationController::class, 'unreadSummary'])->name('modules.chat.unread');
Route::put('/chat/message/{messageId}', [CommunicationController::class, 'updateMessage'])->name('modules.chat.message.update');
Route::delete('/chat/message/{messageId}', [CommunicationController::class, 'deleteMessage'])->name('modules.chat.message.delete');

Route::middleware('module.access:detail_paiement')->group(function () {
    Route::get('/detail-paiement', [AccountingController::class, 'details'])->name('modules.detail-paiement');
    Route::post('/detail-paiement/type', [AccountingController::class, 'storeType'])->name('modules.detail-paiement.type.store');
    Route::post('/detail-paiement/salaires', [AccountingController::class, 'assignSalaryMonths'])->name('modules.detail-paiement.salaires');
    Route::delete('/detail-paiement/type/{id}', [AccountingController::class, 'deleteType'])->name('modules.detail-paiement.type.delete');
});
Route::middleware('module.access:comptable')->group(function () {
    Route::get('/comptable', [AccountingController::class, 'payment'])->name('modules.comptable');
    Route::post('/comptable', [AccountingController::class, 'storePayment'])->name('modules.comptable.store');
    Route::get('/comptable/search-students', [AccountingController::class, 'searchStudents'])->name('modules.comptable.search-students');
});
Route::get('/liste-paiements', [AccountingController::class, 'list'])->middleware('module.access:liste_paiements')->name('modules.liste-paiements');
Route::middleware('module.access:facture')->group(function () {
    Route::get('/facture', [AccountingController::class, 'invoice'])->name('modules.facture');
    Route::get('/facture/search-people', [AccountingController::class, 'searchInvoicePeople'])->name('modules.facture.search-people');
});

Route::get('/rapport-comptable', [ReportController::class, 'accounting'])->middleware('module.access:rapport_comptable')->name('modules.rapport-comptable');
Route::get('/rapport-presence', [ReportController::class, 'teacherPresence'])->middleware('module.access:rapport_presence')->name('modules.rapport-presence');
Route::get('/rapport-staff', [ReportController::class, 'staffPresence'])->middleware('module.access:rapport_staff')->name('modules.rapport-staff');
Route::get('/evaluation-notes', [ReportController::class, 'grades'])->middleware('module.access:evaluation_notes')->name('modules.evaluation-notes');

Route::middleware('module.access:parametres')->group(function () {
    Route::get('/parametres', [SettingsController::class, 'parameters'])->name('modules.parametres');
    Route::post('/parametres', [SettingsController::class, 'saveParameters'])->name('modules.parametres.save');
    Route::get('/parametres/politique-confidentialite', [SettingsController::class, 'privacy'])->name('modules.parametres.privacy');
    Route::get('/parametres/conditions-utilisation', [SettingsController::class, 'terms'])->name('modules.parametres.terms');
});
Route::middleware('module.access:comptes_utilisateurs')->group(function () {
    Route::get('/comptes-utilisateurs', [UserAccountController::class, 'index'])->name('modules.comptes-utilisateurs');
    Route::delete('/comptes-utilisateurs/{id}', [UserAccountController::class, 'destroy'])->name('modules.comptes-utilisateurs.delete');
});
Route::get('/diagnostic-systeme', SystemDiagnosticController::class)->middleware('module.access:diagnostic_systeme')->name('modules.diagnostic-systeme');
Route::get('/apropos-novaskol', [SettingsController::class, 'about'])->middleware('module.access:apropos_novaskol')->name('modules.apropos-novaskol');
Route::middleware('module.access:reseau_local')->group(function () {
    Route::get('/reseau-local', [SettingsController::class, 'localNetwork'])->name('modules.reseau-local');
    Route::post('/reseau-local', [SettingsController::class, 'saveLocalNetwork'])->name('modules.reseau-local.save');
    Route::post('/reseau-local/appairage', [SettingsController::class, 'generatePairingCode'])->name('modules.reseau-local.pairing');
    Route::post('/reseau-local/lots', [SettingsController::class, 'createLocalSyncBatch'])->name('modules.reseau-local.batches.create');
    Route::put('/reseau-local/appareils/{id}', [SettingsController::class, 'renameLocalDevice'])->name('modules.reseau-local.devices.rename');
    Route::post('/reseau-local/appareils/{id}/autorisation', [SettingsController::class, 'toggleLocalDevice'])->name('modules.reseau-local.devices.toggle');
    Route::delete('/reseau-local/appareils/{id}', [SettingsController::class, 'deleteLocalDevice'])->name('modules.reseau-local.devices.delete');
});
Route::get('/guide-utilisation', [SettingsController::class, 'guide'])->middleware('module.access:guide_utilisation')->name('modules.guide-utilisation');
Route::middleware('module.access:sauvegardes')->group(function () {
    Route::get('/sauvegardes', [SettingsController::class, 'backups'])->name('modules.sauvegardes');
    Route::post('/sauvegardes', [SettingsController::class, 'createBackup'])->name('modules.sauvegardes.create');
    Route::post('/sauvegardes/{file}/restore', [SettingsController::class, 'restoreBackup'])->name('modules.sauvegardes.restore');
    Route::get('/sauvegardes/{file}/download', [SettingsController::class, 'downloadBackup'])->name('modules.sauvegardes.download');
    Route::delete('/sauvegardes/{file}', [SettingsController::class, 'deleteBackup'])->name('modules.sauvegardes.delete');
});


