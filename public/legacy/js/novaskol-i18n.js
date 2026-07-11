(function () {
  const languages = {
    fr: 'Francais',
    en: 'English',
    de: 'Deutsch',
    mg: 'Malagasy',
    es: 'Espanol',
    pt: 'Portugues',
  };

  const dict = { en: {}, de: {}, mg: {}, es: {}, pt: {} };
  const normalize = (value) => String(value || '')
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[`"'’‘“”«»<>[\]{}_|/\\-]/g, ' ')
    .replace(/[.,;:!?()&]/g, ' ')
    .replace(/\s+/g, ' ')
    .trim()
    .toLowerCase();

  function add(fr, en, de, mg, es, pt) {
    const key = normalize(fr);
    dict.en[key] = en;
    dict.de[key] = de;
    dict.mg[key] = mg;
    dict.es[key] = es;
    dict.pt[key] = pt;
  }

  [
    ['Accueil', 'Home', 'Startseite', 'Fandraisana', 'Inicio', 'Inicio'],
    ['Mon espace', 'My space', 'Mein Bereich', 'Sehatra manokana', 'Mi espacio', 'Meu espaco'],
    ['Suivi des enfants', 'Children tracking', 'Kinderubersicht', 'Fanaraha-maso ny ankizy', 'Seguimiento de hijos', 'Acompanhamento dos filhos'],
    ['Tableau de bord', 'Dashboard', 'Dashboard', 'Tabilao fandraisana', 'Panel principal', 'Painel principal'],
    ['Ecole', 'School', 'Schule', 'Sekoly', 'Escuela', 'Escola'],
    ['Administration', 'Administration', 'Verwaltung', 'Fitantanana', 'Administracion', 'Administracao'],
    ['Inscription', 'Registration', 'Anmeldung', 'Fisoratana', 'Inscripcion', 'Inscricao'],
    ['Liste classes', 'Class list', 'Klassenliste', 'Lisitrin ny kilasy', 'Lista de clases', 'Lista de turmas'],
    ['Matieres', 'Subjects', 'Facher', 'Taranja', 'Materias', 'Disciplinas'],
    ['Professeur', 'Teacher', 'Lehrkraft', 'Mpampianatra', 'Profesor', 'Professor'],
    ['Ajouts des Notes', 'Grade entry', 'Notenerfassung', 'Fampidirana naoty', 'Registro de notas', 'Lancamento de notas'],
    ['Bulletin', 'Report card', 'Zeugnis', 'Bulletin', 'Boletin', 'Boletim'],
    ['Resultat', 'Results', 'Ergebnisse', 'Valiny', 'Resultados', 'Resultados'],
    ['Examen Blanc', 'Mock exam', 'Probeprufung', 'Fanadinana andrana', 'Examen simulado', 'Exame simulado'],
    ['Resultats Examen Blanc', 'Mock exam results', 'Probeprufung Ergebnisse', 'Valin ny fanadinana andrana', 'Resultados del examen simulado', 'Resultados do exame simulado'],
    ['Pedagogique', 'Pedagogy', 'Padagogik', 'Pedagojika', 'Pedagogico', 'Pedagogico'],
    ['Emploi du temps', 'Timetable', 'Stundenplan', 'Fandaharam-potoana', 'Horario', 'Horario'],
    ['Fiche de presence', 'Attendance sheet', 'Anwesenheitsliste', 'Taratasy fanatrehana', 'Hoja de asistencia', 'Folha de presenca'],
    ['Calendrier academique', 'Academic calendar', 'Akademischer Kalender', 'Kalandrie akademika', 'Calendario academico', 'Calendario academico'],
    ['Notifications', 'Notifications', 'Benachrichtigungen', 'Fampandrenesana', 'Notificaciones', 'Notificacoes'],
    ['Generation de cartes', 'Card generation', 'Kartenerstellung', 'Famokarana karatra', 'Generacion de tarjetas', 'Geracao de cartoes'],
    ['Depot Dossier', 'File deposit', 'Ablage', 'Fametrahana dosie', 'Deposito de expediente', 'Deposito de documentos'],
    ['FPE (Effectifs)', 'FPE (enrolment)', 'FPE (Bestande)', 'FPE (isan olona)', 'FPE (efectivos)', 'FPE (efetivos)'],
    ['Liste Assurance', 'Insurance list', 'Versicherungsliste', 'Lisitry ny fiantohana', 'Lista de seguros', 'Lista de seguros'],
    ['Ressource Humaine', 'Human resources', 'Personalwesen', 'Harena olombelona', 'Recursos humanos', 'Recursos humanos'],
    ['Enseignants', 'Teachers', 'Lehrkrafte', 'Mpampianatra', 'Profesores', 'Professores'],
    ['Staff', 'Staff', 'Personal', 'Mpiasa', 'Personal', 'Equipe'],
    ['Presence', 'Attendance', 'Anwesenheit', 'Fanatrehana', 'Asistencia', 'Presenca'],
    ['Presence staff', 'Staff attendance', 'Personal Anwesenheit', 'Fanatrehan ny mpiasa', 'Asistencia del personal', 'Presenca da equipe'],
    ['Permissions', 'Permissions', 'Berechtigungen', 'Fahazoan-dalana', 'Permisos', 'Permissoes'],
    ['Gestion des ressources', 'Resource management', 'Ressourcenverwaltung', 'Fitantanana loharano', 'Gestion de recursos', 'Gestao de recursos'],
    ['Communication', 'Communication', 'Kommunikation', 'Fifandraisana', 'Comunicacion', 'Comunicacao'],
    ['Chat Prive', 'Private chat', 'Privater Chat', 'Resaka manokana', 'Chat privado', 'Chat privado'],
    ['Chat Groupe', 'Group chat', 'Gruppenchat', 'Resaka vondrona', 'Chat grupal', 'Chat de grupo'],
    ['Comptable', 'Accounting', 'Buchhaltung', 'Kaonty', 'Contabilidad', 'Contabilidade'],
    ['Details Paiement', 'Payment details', 'Zahlungsdetails', 'Antsipiriany fandoavana', 'Detalles de pago', 'Detalhes do pagamento'],
    ['Liste des paiements', 'Payment list', 'Zahlungsliste', 'Lisitry ny fandoavana', 'Lista de pagos', 'Lista de pagamentos'],
    ['Facture & Recu', 'Invoice & receipt', 'Rechnung & Quittung', 'Faktiora sy rosia', 'Factura y recibo', 'Fatura e recibo'],
    ['Rapport', 'Reports', 'Berichte', 'Tatitra', 'Informes', 'Relatorios'],
    ['Rapport Comptable', 'Accounting report', 'Buchhaltungsbericht', 'Tatitra kaonty', 'Informe contable', 'Relatorio contabil'],
    ['Rapport professeur', 'Teacher report', 'Lehrerbericht', 'Tatitra mpampianatra', 'Informe profesor', 'Relatorio professor'],
    ['Rapport staff', 'Staff report', 'Personalbericht', 'Tatitra mpiasa', 'Informe personal', 'Relatorio da equipe'],
    ['Evaluation des notes', 'Grade analysis', 'Notenanalyse', 'Fanombanana naoty', 'Evaluacion de notas', 'Avaliacao de notas'],
    ['Parametre', 'Settings', 'Einstellungen', 'Fikirana', 'Configuracion', 'Configuracoes'],
    ['Parametres', 'Settings', 'Einstellungen', 'Fikirana', 'Configuracion', 'Configuracoes'],
    ['Comptes utilisateurs', 'User accounts', 'Benutzerkonten', 'Kaonty mpampiasa', 'Cuentas de usuario', 'Contas de usuario'],
    ['Diagnostic systeme', 'System diagnostics', 'Systemdiagnose', 'Fanamarinana rafitra', 'Diagnostico del sistema', 'Diagnostico do sistema'],
    ['A propos Novaskol', 'About Novaskol', 'Uber Novaskol', 'Momba an i Novaskol', 'Acerca de Novaskol', 'Sobre o Novaskol'],
    ['Appareil principal', 'Main device', 'Hauptgerat', 'Fitaovana fototra', 'Dispositivo principal', 'Dispositivo principal'],
    ['Guide utilisation', 'User guide', 'Benutzerhandbuch', 'Torolalana fampiasana', 'Guia de uso', 'Guia de uso'],
    ['Sauvegardes', 'Backups', 'Sicherungen', 'Tahiry fiarovana', 'Copias de seguridad', 'Copias de seguranca'],

    ['Langue active : Francais', 'Active language: French', 'Aktive Sprache: Franzosisch', 'Fiteny voafidy: Frantsay', 'Idioma activo: Frances', 'Idioma ativo: Frances'],
    ['Langue', 'Language', 'Sprache', 'Fiteny', 'Idioma', 'Idioma'],
    ['Mode sombre / clair', 'Dark / light mode', 'Dunkel / hell', 'Maizina / mazava', 'Modo oscuro / claro', 'Modo escuro / claro'],
    ['Changer de theme', 'Change theme', 'Design wechseln', 'Hanova theme', 'Cambiar tema', 'Alterar tema'],
    ['Notifications non lues', 'Unread notifications', 'Ungelesene Benachrichtigungen', 'Fampandrenesana tsy mbola voavaky', 'Notificaciones no leidas', 'Notificacoes nao lidas'],
    ['Voir toutes les notifications', 'View all notifications', 'Alle Benachrichtigungen anzeigen', 'Hijery ny fampandrenesana rehetra', 'Ver todas las notificaciones', 'Ver todas as notificacoes'],
    ['Tout marquer lu', 'Mark all as read', 'Alle als gelesen markieren', 'Ataovy voavaky rehetra', 'Marcar todo como leido', 'Marcar tudo como lido'],
    ['Messages', 'Messages', 'Nachrichten', 'Hafatra', 'Mensajes', 'Mensagens'],
    ['Profil', 'Profile', 'Profil', 'Mombamomba', 'Perfil', 'Perfil'],
    ['Compte', 'Account', 'Konto', 'Kaonty', 'Cuenta', 'Conta'],
    ['Voir tout', 'View all', 'Alle anzeigen', 'Hijery rehetra', 'Ver todo', 'Ver tudo'],
    ['Ouvrir chat', 'Open chat', 'Chat offnen', 'Sokafy ny resaka', 'Abrir chat', 'Abrir chat'],
    ['Aucune notification.', 'No notification.', 'Keine Benachrichtigung.', 'Tsy misy fampandrenesana.', 'Sin notificaciones.', 'Sem notificacoes.'],
    ['Aucun message recent.', 'No recent message.', 'Keine aktuelle Nachricht.', 'Tsy misy hafatra vao haingana.', 'Sin mensajes recientes.', 'Sem mensagens recentes.'],
    ['Lu', 'Read', 'Gelesen', 'Voavaky', 'Leido', 'Lido'],
    ['Supprimer', 'Delete', 'Loschen', 'Fafao', 'Eliminar', 'Eliminar'],
    ['Deconnexion', 'Logout', 'Abmelden', 'Hivoaka', 'Cerrar sesion', 'Sair'],
    ['Chargement...', 'Loading...', 'Wird geladen...', 'Miandry...', 'Cargando...', 'Carregando...'],
    ['Succes', 'Success', 'Erfolg', 'Vita soa', 'Exito', 'Sucesso'],
    ['Erreur', 'Error', 'Fehler', 'Hadisoana', 'Error', 'Erro'],
    ['Information', 'Information', 'Information', 'Fampahalalana', 'Informacion', 'Informacao'],
    ['Confirmer la suppression ?', 'Confirm deletion?', 'Loschen bestatigen?', 'Hamafiso ny famafana?', 'Confirmar eliminacion?', 'Confirmar exclusao?'],
    ['Confirmer l enregistrement ?', 'Confirm saving?', 'Speichern bestatigen?', 'Hamafiso ny fitahirizana?', 'Confirmar guardado?', 'Confirmar salvamento?'],
    ['Cette action est definitive.', 'This action is final.', 'Diese Aktion ist endgultig.', 'Tsy azo averina ity asa ity.', 'Esta accion es definitiva.', 'Esta acao e definitiva.'],
    ['Les informations seront enregistrees.', 'The information will be saved.', 'Die Informationen werden gespeichert.', 'Hotahirizina ny vaovao.', 'La informacion sera guardada.', 'As informacoes serao salvas.'],
    ['Oui, supprimer', 'Yes, delete', 'Ja, loschen', 'Eny, fafao', 'Si, eliminar', 'Sim, eliminar'],
    ['Oui, continuer', 'Yes, continue', 'Ja, fortfahren', 'Eny, tohizo', 'Si, continuar', 'Sim, continuar'],
    ['Annuler', 'Cancel', 'Abbrechen', 'Aoka ihany', 'Cancelar', 'Cancelar'],
    ['Supprimer ?', 'Delete?', 'Loschen?', 'Fafaina?', 'Eliminar?', 'Eliminar?'],
    ['Cette notification sera supprimee.', 'This notification will be deleted.', 'Diese Benachrichtigung wird geloscht.', 'Ho fafana ity fampandrenesana ity.', 'Esta notificacion sera eliminada.', 'Esta notificacao sera eliminada.'],

    ['Nom', 'Name', 'Name', 'Anarana', 'Nombre', 'Nome'],
    ['Prenom', 'First name', 'Vorname', 'Fanampin anarana', 'Nombre', 'Nome proprio'],
    ['Nom complet', 'Full name', 'Vollstandiger Name', 'Anarana feno', 'Nombre completo', 'Nome completo'],
    ['Email', 'Email', 'E-Mail', 'Mailaka', 'Correo', 'Email'],
    ['Telephone', 'Phone', 'Telefon', 'Finday', 'Telefono', 'Telefone'],
    ['Mot de passe', 'Password', 'Passwort', 'Teny miafina', 'Contrasena', 'Senha'],
    ['Nouveau mot de passe', 'New password', 'Neues Passwort', 'Teny miafina vaovao', 'Nueva contrasena', 'Nova senha'],
    ['Confirmation', 'Confirmation', 'Bestatigung', 'Fanamafisana', 'Confirmacion', 'Confirmacao'],
    ['Photo de profil', 'Profile photo', 'Profilfoto', 'Sary mombamomba', 'Foto de perfil', 'Foto de perfil'],
    ['Enregistrer', 'Save', 'Speichern', 'Tehirizo', 'Guardar', 'Salvar'],
    ['Modifier', 'Edit', 'Bearbeiten', 'Hanova', 'Modificar', 'Editar'],
    ['Ajouter', 'Add', 'Hinzufugen', 'Ampio', 'Agregar', 'Adicionar'],
    ['Filtrer', 'Filter', 'Filtern', 'Sivano', 'Filtrar', 'Filtrar'],
    ['Imprimer', 'Print', 'Drucken', 'Ataovy printy', 'Imprimir', 'Imprimir'],
    ['Rechercher', 'Search', 'Suchen', 'Hikaroka', 'Buscar', 'Pesquisar'],
    ['Choisir', 'Choose', 'Auswahlen', 'Misafidy', 'Elegir', 'Escolher'],
    ['Fermer', 'Close', 'Schliessen', 'Akatona', 'Cerrar', 'Fechar'],
    ['Payer', 'Pay', 'Bezahlen', 'Mandoa', 'Pagar', 'Pagar'],
    ['Retour', 'Back', 'Zuruck', 'Hiverina', 'Volver', 'Voltar'],
    ['Actions', 'Actions', 'Aktionen', 'Asa', 'Acciones', 'Acoes'],
    ['Annee', 'Year', 'Jahr', 'Taona', 'Ano', 'Ano'],
    ['Annee scolaire', 'School year', 'Schuljahr', 'Taom-pianarana', 'Ano escolar', 'Ano letivo'],
    ['Mois', 'Month', 'Monat', 'Volana', 'Mes', 'Mes'],
    ['Jour', 'Day', 'Tag', 'Andro', 'Dia', 'Dia'],
    ['Date', 'Date', 'Datum', 'Daty', 'Fecha', 'Data'],
    ['Classe', 'Class', 'Klasse', 'Kilasy', 'Clase', 'Turma'],
    ['Matiere', 'Subject', 'Fach', 'Taranja', 'Materia', 'Disciplina'],
    ['Periode', 'Period', 'Zeitraum', 'Fotoana', 'Periodo', 'Periodo'],
    ['Eleve', 'Student', 'Schuler', 'Mpianatra', 'Alumno', 'Aluno'],
    ['Etudiant', 'Student', 'Schuler', 'Mpianatra', 'Estudiante', 'Estudante'],
    ['Parent', 'Parent', 'Elternteil', 'Ray aman-dreny', 'Padre/Madre', 'Responsavel'],
    ['Administrateur', 'Administrator', 'Administrator', 'Mpitantana', 'Administrador', 'Administrador'],
    ['Personnel', 'Staff', 'Personal', 'Mpiasa', 'Personal', 'Pessoal'],
    ['Votre role', 'Your role', 'Ihre Rolle', 'Anjaranao', 'Su rol', 'Seu papel'],
    ['Se connecter', 'Sign in', 'Anmelden', 'Hiditra', 'Iniciar sesion', 'Entrar'],
    ["S'inscrire", 'Sign up', 'Registrieren', 'Hisoratra', 'Registrarse', 'Cadastrar-se'],
    ['Creer un compte', 'Create an account', 'Konto erstellen', 'Mamorona kaonty', 'Crear cuenta', 'Criar conta'],
    ['Pas de compte ?', 'No account?', 'Noch kein Konto?', 'Tsy manana kaonty?', 'No tiene cuenta?', 'Sem conta?'],
    ['Deja inscrit ?', 'Already registered?', 'Bereits registriert?', 'Efa voasoratra?', 'Ya registrado?', 'Ja cadastrado?'],
    ['Confirmer', 'Confirm', 'Bestatigen', 'Hamafiso', 'Confirmar', 'Confirmar'],
    ['Selectionner une classe', 'Select a class', 'Klasse auswahlen', 'Misafidiana kilasy', 'Seleccionar una clase', 'Selecionar turma'],
    ['Selectionne une classe pour afficher le tableau des notes.', 'Select a class to display the grade table.', 'Wahlen Sie eine Klasse, um die Notentabelle anzuzeigen.', 'Misafidiana kilasy hanehoana ny tabilao naoty.', 'Seleccione una clase para mostrar la tabla de notas.', 'Selecione uma turma para exibir a tabela de notas.'],
    ['Ajout des notes par classe', 'Grade entry by class', 'Notenerfassung nach Klasse', 'Fampidirana naoty isaky ny kilasy', 'Notas por clase', 'Lancamento de notas por turma'],
    ['1er Trimestre', '1st term', '1. Trimester', 'Trimester voalohany', '1er trimestre', '1o trimestre'],
    ['2eme Trimestre', '2nd term', '2. Trimester', 'Trimester faharoa', '2o trimestre', '2o trimestre'],
    ['3eme Trimestre', '3rd term', '3. Trimester', 'Trimester fahatelo', '3er trimestre', '3o trimestre'],
    ['Informations generales', 'General information', 'Allgemeine Informationen', 'Fampahalalana ankapobeny', 'Informacion general', 'Informacoes gerais'],
    ['Details de l ecole', 'School details', 'Schuldaten', 'Antsipirian ny sekoly', 'Detalles de la escuela', 'Detalhes da escola'],
    ['Nom de l ecole', 'School name', 'Schulname', 'Anaran ny sekoly', 'Nombre de la escuela', 'Nome da escola'],
    ['Adresse', 'Address', 'Adresse', 'Adiresy', 'Direccion', 'Endereco'],
    ['Directeur', 'Director', 'Direktor', 'Tale', 'Director', 'Diretor'],
    ['Logo', 'Logo', 'Logo', 'Logo', 'Logo', 'Logo'],
    ['Liste des eleves', 'Student list', 'Schulerliste', 'Lisitry ny mpianatra', 'Lista de alumnos', 'Lista de alunos'],
    ['Ajouter un eleve', 'Add student', 'Schuler hinzufugen', 'Ampio mpianatra', 'Agregar alumno', 'Adicionar aluno'],
    ['Modifier un eleve', 'Edit student', 'Schuler bearbeiten', 'Hanova mpianatra', 'Modificar alumno', 'Editar aluno'],
    ['Importer', 'Import', 'Importieren', 'Ampidiro', 'Importar', 'Importar'],
    ['Exporter', 'Export', 'Exportieren', 'Avoahy', 'Exportar', 'Exportar'],
    ['Aucun eleve trouve.', 'No student found.', 'Kein Schuler gefunden.', 'Tsy misy mpianatra hita.', 'No se encontro ningun alumno.', 'Nenhum aluno encontrado.'],
    ['Ajouter une classe', 'Add class', 'Klasse hinzufugen', 'Ampio kilasy', 'Agregar clase', 'Adicionar turma'],
    ['Modifier une classe', 'Edit class', 'Klasse bearbeiten', 'Hanova kilasy', 'Modificar clase', 'Editar turma'],
    ['Aucune classe trouvee.', 'No class found.', 'Keine Klasse gefunden.', 'Tsy misy kilasy hita.', 'No se encontro ninguna clase.', 'Nenhuma turma encontrada.'],
    ['Liste des classes', 'Class list', 'Klassenliste', 'Lisitry ny kilasy', 'Lista de clases', 'Lista de turmas'],
    ['Liste des eleves par classe', 'Students by class', 'Schuler nach Klasse', 'Mpianatra isaky ny kilasy', 'Alumnos por clase', 'Alunos por turma'],
    ['Toutes les annees', 'All years', 'Alle Jahre', 'Taona rehetra', 'Todos los anos', 'Todos os anos'],
    ['Niveau', 'Level', 'Stufe', 'Ambaratonga', 'Nivel', 'Nivel'],
    ['Ambaratonga', 'Level', 'Stufe', 'Ambaratonga', 'Nivel', 'Nivel'],
    ['Les enseignants ajoutent les notes de chaque eleve', 'Teachers enter each student grades', 'Lehrkrafte tragen die Noten jedes Schulers ein', 'Ny mpampianatra no mampiditra ny naotin ny mpianatra tsirairay', 'Los profesores ingresan las notas de cada alumno', 'Os professores lancam as notas de cada aluno'],
    ['Ajouter une matiere', 'Add subject', 'Fach hinzufugen', 'Ampio taranja', 'Agregar materia', 'Adicionar disciplina'],
    ['Modifier une matiere', 'Edit subject', 'Fach bearbeiten', 'Hanova taranja', 'Modificar materia', 'Editar disciplina'],
    ['Aucune matiere trouvee.', 'No subject found.', 'Kein Fach gefunden.', 'Tsy misy taranja hita.', 'No se encontro ninguna materia.', 'Nenhuma disciplina encontrada.'],
    ['Gestion des matieres', 'Subject management', 'Fachverwaltung', 'Fitantanana taranja', 'Gestion de materias', 'Gestao de disciplinas'],
    ['Nouvelle matiere...', 'New subject...', 'Neues Fach...', 'Taranja vaovao...', 'Nueva materia...', 'Nova disciplina...'],
    ['Save', 'Save', 'Speichern', 'Tehirizo', 'Guardar', 'Salvar'],
    ['Coefficient', 'Coefficient', 'Koeffizient', 'Coefficient', 'Coeficiente', 'Coeficiente'],
    ['Enregistrer les modifications', 'Save changes', 'Anderungen speichern', 'Tehirizo ny fanovana', 'Guardar cambios', 'Salvar alteracoes'],
    ['Tous droits reserves.', 'All rights reserved.', 'Alle Rechte vorbehalten.', 'Zo rehetra voatokana.', 'Todos los derechos reservados.', 'Todos os direitos reservados.'],
    ['NB : Vous devez ajouter un seul logo !', 'Note: add only one logo!', 'Hinweis: Fugen Sie nur ein Logo hinzu!', 'Fanamarihana: logo iray ihany no ampidirina!', 'Nota: agregue un solo logotipo.', 'Nota: adicione apenas um logotipo.'],
    ['Logo de l ecole :', 'School logo:', 'Schullogo:', 'Logon ny sekoly:', 'Logo de la escuela:', 'Logo da escola:'],
    ['Entrer ici le nom de votre etablissement', 'Enter your institution name here', 'Geben Sie hier den Namen Ihrer Einrichtung ein', 'Ampidiro eto ny anaran ny sekolinao', 'Ingrese aqui el nombre de su institucion', 'Digite aqui o nome da sua instituicao'],
    ["Inscription d'un eleve", 'Student registration', 'Schuleranmeldung', 'Fisoratana mpianatra', 'Inscripcion de alumno', 'Inscricao de aluno'],
    ['Rechercher par nom, prenom ou matricule', 'Search by name, first name or ID', 'Nach Name, Vorname oder Nummer suchen', 'Karohy amin ny anarana, fanampin anarana na laharana', 'Buscar por nombre, apellido o matricula', 'Pesquisar por nome, sobrenome ou matricula'],
    ['-- Choisir une classe --', '-- Choose a class --', '-- Klasse auswahlen --', '-- Misafidiana kilasy --', '-- Elegir una clase --', '-- Escolher uma turma --'],
    ['Imprimer Photo', 'Print photo', 'Foto drucken', 'Ataovy printy ny sary', 'Imprimir foto', 'Imprimir foto'],
    ['Modele', 'Template', 'Vorlage', 'Modely', 'Plantilla', 'Modelo'],
    ['Aucun resultat.', 'No result.', 'Kein Ergebnis.', 'Tsy misy valiny.', 'Sin resultado.', 'Sem resultado.'],
    ['Aucune donnee disponible.', 'No data available.', 'Keine Daten verfugbar.', 'Tsy misy angona.', 'No hay datos disponibles.', 'Nenhum dado disponivel.'],
    ['Aucun enregistrement.', 'No record.', 'Kein Eintrag.', 'Tsy misy rakitra.', 'Sin registro.', 'Nenhum registro.'],
    ['Tout le monde', 'Everyone', 'Alle', 'Rehetra', 'Todos', 'Todos'],
    ['Statut', 'Status', 'Status', 'Sata', 'Estado', 'Status'],
    ['Actif', 'Active', 'Aktiv', 'Mavitrika', 'Activo', 'Ativo'],
    ['Inactif', 'Inactive', 'Inaktiv', 'Tsy mavitrika', 'Inactivo', 'Inativo'],
    ['Recherche', 'Search', 'Suche', 'Fikarohana', 'Busqueda', 'Pesquisa'],
    ['Appliquer', 'Apply', 'Anwenden', 'Ampiharo', 'Aplicar', 'Aplicar'],
    ['Reinitialiser', 'Reset', 'Zurucksetzen', 'Avereno', 'Restablecer', 'Redefinir'],
    ['Details', 'Details', 'Details', 'Antsipiriany', 'Detalles', 'Detalhes'],
    ['Voir', 'View', 'Ansehen', 'Hijery', 'Ver', 'Ver'],
    ['Valider', 'Validate', 'Bestatigen', 'Hamarino', 'Validar', 'Validar'],
    ['Enregistrer les notes', 'Save grades', 'Noten speichern', 'Tehirizo ny naoty', 'Guardar notas', 'Salvar notas'],
    ['Remarque', 'Remark', 'Bemerkung', 'Fanamarihana', 'Observacion', 'Observacao'],
    ['Moyenne', 'Average', 'Durchschnitt', 'Salanisa', 'Promedio', 'Media'],
    ['Mention', 'Mention', 'Bewertung', 'Fanamarihana', 'Mencion', 'Mencao'],
    ['Rang', 'Rank', 'Rang', 'Laharana', 'Rango', 'Classificacao'],
    ['Observation', 'Observation', 'Beobachtung', 'Fanamarihana', 'Observacion', 'Observacao'],

    ['Total', 'Total', 'Gesamt', 'Fitambarany', 'Total', 'Total'],
    ['Montant', 'Amount', 'Betrag', 'Vola', 'Importe', 'Valor'],
    ['Reste', 'Remaining', 'Rest', 'Sisa', 'Resta', 'Restante'],
    ['Paye', 'Paid', 'Bezahlt', 'Voaloa', 'Pagado', 'Pago'],
    ['Non paye', 'Unpaid', 'Unbezahlt', 'Tsy voaloa', 'No pagado', 'Nao pago'],
    ['Partiel', 'Partial', 'Teilweise', 'Ampahany', 'Parcial', 'Parcial'],
    ['Complet', 'Complete', 'Vollstandig', 'Feno', 'Completo', 'Completo'],
    ['Revenus', 'Income', 'Einnahmen', 'Vola miditra', 'Ingresos', 'Receitas'],
    ['Depenses', 'Expenses', 'Ausgaben', 'Fandaniana', 'Gastos', 'Despesas'],
    ['Facture', 'Invoice', 'Rechnung', 'Faktiora', 'Factura', 'Fatura'],
    ['Recu', 'Receipt', 'Quittung', 'Rosia', 'Recibo', 'Recibo'],
    ['Salaire', 'Salary', 'Gehalt', 'Karama', 'Salario', 'Salario'],
    ['Paie estimee', 'Estimated pay', 'Geschatzte Zahlung', 'Karama tombanana', 'Pago estimado', 'Pagamento estimado'],
    ['Presence / absence par personne', 'Attendance / absence by person', 'Anwesenheit / Abwesenheit pro Person', 'Fanatrehana / tsy fanatrehana isaky ny olona', 'Asistencia / ausencia por persona', 'Presenca / ausencia por pessoa'],
    ['Evolution hebdomadaire', 'Weekly evolution', 'Wochentliche Entwicklung', 'Fivoarana isan-kerinandro', 'Evolucion semanal', 'Evolucao semanal'],
    ['Evolution mensuelle', 'Monthly evolution', 'Monatliche Entwicklung', 'Fivoarana isam-bolana', 'Evolucion mensual', 'Evolucao mensal'],
    ['Fiche de paie', 'Payslip', 'Lohnzettel', 'Taratasy karama', 'Nomina', 'Recibo de salario'],
    ['Details de presence', 'Attendance details', 'Anwesenheitsdetails', 'Antsipiriany fanatrehana', 'Detalles de asistencia', 'Detalhes de presenca'],

    ['Present', 'Present', 'Anwesend', 'Tonga', 'Presente', 'Presente'],
    ['Absent', 'Absent', 'Abwesend', 'Tsy tonga', 'Ausente', 'Ausente'],
    ['Retard', 'Late', 'Verspatet', 'Tara', 'Retraso', 'Atraso'],
    ['Oui', 'Yes', 'Ja', 'Eny', 'Si', 'Sim'],
    ['Non', 'No', 'Nein', 'Tsia', 'No', 'Nao'],
    ['Heures', 'Hours', 'Stunden', 'Ora', 'Horas', 'Horas'],
    ['Jours', 'Days', 'Tage', 'Andro', 'Dias', 'Dias'],
    ['Presences du', 'Attendance for', 'Anwesenheiten vom', 'Fanatrehana tamin ny', 'Asistencias del', 'Presencas de'],
    ['Ajouter une presence', 'Add attendance', 'Anwesenheit hinzufugen', 'Ampio fanatrehana', 'Agregar asistencia', 'Adicionar presenca'],
    ['Presence geree par l\'administration', 'Attendance managed by administration', 'Anwesenheit wird von der Verwaltung verwaltet', 'Ny fanatrehana tantanan ny fitantanana', 'Asistencia gestionada por administracion', 'Presenca gerida pela administracao'],
    ['Pour proteger les heures de travail et eviter toute modification non autorisee, l historique et les actions de presence sont reserves aux administrateurs et au staff RH du departement Administration.', 'To protect working hours and prevent unauthorized changes, attendance history and actions are reserved for administrators and HR staff in the Administration department.', 'Zum Schutz der Arbeitszeiten und zur Vermeidung unbefugter Anderungen sind Anwesenheitsverlauf und Aktionen Administratoren und HR-Personal der Verwaltung vorbehalten.', 'Mba hiarovana ny ora fiasana sy hisorohana fanovana tsy nahazoana alalana dia natokana ho an ny mpitantana sy RH ao amin ny departemanta Administration ny tantara sy ny asa fanatrehana.', 'Para proteger las horas de trabajo y evitar cambios no autorizados, el historial y las acciones de asistencia estan reservados a administradores y RRHH de Administracion.', 'Para proteger as horas de trabalho e evitar alteracoes nao autorizadas, o historico e as acoes de presenca ficam reservados aos administradores e RH da Administracao.'],

    ['Gestion scolaire moderne internationale', 'Modern international school management', 'Moderne internationale Schulverwaltung', 'Fitantanana sekoly maoderina iraisam-pirenena', 'Gestion escolar moderna internacional', 'Gestao escolar moderna internacional'],
    ['Commencer maintenant', 'Start now', 'Jetzt starten', 'Atombohy izao', 'Comenzar ahora', 'Comecar agora'],
    ['Demander une demo', 'Request a demo', 'Demo anfragen', 'Mangataka demo', 'Solicitar una demo', 'Solicitar demo'],
    ['pour chaque ecole', 'for every school', 'fur jede Schule', 'ho an ny sekoly tsirairay', 'para cada escuela', 'para cada escola'],
    ['Une solution scolaire moderne : installable en local, partageable ecole par ecole, et prete a etre hebergee en ligne quand l etablissement le souhaite.', 'A modern school solution: install locally, share school by school, and host online whenever the institution is ready.', 'Eine moderne Schullosung: lokal installierbar, Schule fur Schule teilbar und online hostbar, sobald die Einrichtung es wunscht.', 'Vahaolana sekoly maoderina: azo apetraka local, zaraina isaky ny sekoly, ary azo ampiantranoina an-tserasera rehefa vonona ny sekoly.', 'Una solucion escolar moderna: instalable localmente, distribuible escuela por escuela y lista para alojarse en linea cuando la institucion lo desee.', 'Uma solucao escolar moderna: instalavel localmente, distribuivel escola por escola e pronta para hospedagem online quando a instituicao desejar.'],
    ['Un systeme de gestion scolaire moderne : installable en local, partageable ecole par ecole, et pret a etre heberge en ligne quand l etablissement le souhaite.', 'A modern school management system: installable locally, shareable school by school, and ready to be hosted online when the institution wants it.', 'Ein modernes Schulverwaltungssystem: lokal installierbar, Schule fur Schule teilbar und bereit fur Online-Hosting, wenn die Einrichtung es wunscht.', 'Rafitra fitantanana sekoly maoderina: azo apetraka eto an-toerana, zaraina isaky ny sekoly, ary vonona hapetraka an-tserasera rehefa ilain ny sekoly.', 'Un sistema de gestion escolar moderno: instalable localmente, compartible escuela por escuela y listo para alojarse en linea cuando la institucion lo desee.', 'Um sistema de gestao escolar moderno: instalavel localmente, compartilhavel escola por escola e pronto para hospedagem online quando a instituicao desejar.'],
    ['Etablissements', 'Institutions', 'Einrichtungen', 'Sekoly', 'Instituciones', 'Instituicoes'],
    ['Eleves connectes', 'Connected students', 'Verbundene Schuler', 'Mpianatra mifandray', 'Alumnos conectados', 'Alunos conectados'],
    ['Satisfaction', 'Satisfaction', 'Zufriedenheit', 'Fahafaham-po', 'Satisfaccion', 'Satisfacao'],
    ['Qui sommes-nous ?', 'Who are we?', 'Wer sind wir?', 'Iza izahay?', 'Quienes somos?', 'Quem somos?'],
    ['Creee a Antananarivo, Novaskol repond a un besoin simple : permettre aux ecoles de gerer leurs donnees elles-memes, meme sans connexion permanente. Chaque etablissement peut l utiliser sur un ordinateur local, puis le mettre en ligne plus tard avec son propre domaine.', 'Created in Antananarivo, Novaskol answers a simple need: allowing schools to manage their own data, even without a permanent connection. Each institution can use it on a local computer, then put it online later with its own domain.', 'In Antananarivo entwickelt, beantwortet Novaskol ein einfaches Bedurfnis: Schulen konnen ihre Daten selbst verwalten, auch ohne dauerhafte Verbindung. Jede Einrichtung kann es lokal nutzen und spater mit eigener Domain online stellen.', 'Noforonina teto Antananarivo, Novaskol dia mamaly filana tsotra: ahafahan ny sekoly mitantana ny angon-drakiny, na tsy misy connexion maharitra aza. Afaka mampiasa azy local ny sekoly tsirairay ary mampakatra azy an-tserasera amin ny domaine-ny avy eo.', 'Creado en Antananarivo, Novaskol responde a una necesidad simple: permitir que las escuelas gestionen sus propios datos, incluso sin conexion permanente. Cada institucion puede usarlo localmente y luego ponerlo en linea con su propio dominio.', 'Criado em Antananarivo, o Novaskol responde a uma necessidade simples: permitir que as escolas gerenciem seus proprios dados, mesmo sem conexao permanente. Cada instituicao pode usa-lo localmente e depois publica-lo online com seu proprio dominio.'],
    ['Pensee pour les ecoles modernes', 'Designed for modern schools', 'Fur moderne Schulen entwickelt', 'Natao ho an ny sekoly maoderina', 'Pensado para escuelas modernas', 'Pensado para escolas modernas'],
    ['Fonctionnement local, sauvegardes, paiements echelonnes, calendriers scolaires et modules adaptes aux realites des etablissements.', 'Local operation, backups, instalment payments, school calendars and modules adapted to each institution.', 'Lokaler Betrieb, Sicherungen, Ratenzahlungen, Schulkalender und Module passend zur Realitat der Einrichtungen.', 'Fampiasana local, sauvegarde, fandoavana tsikelikely, kalandrie sekoly ary modules mifanaraka amin ny zava-misy.', 'Funcionamiento local, copias de seguridad, pagos por plazos, calendarios escolares y modulos adaptados.', 'Funcionamento local, backups, pagamentos parcelados, calendarios escolares e modulos adaptados.'],
    ['Ultra-moderne', 'Ultra modern', 'Ultramodern', 'Maoderina be', 'Ultramoderno', 'Ultramoderno'],
    ['Interface fluide, notifications, bulletins automatiques, chat prive, canal officiel d annonces et espaces par role.', 'Fluid interface, notifications, automatic report cards, private chat, official announcement channel and role-based spaces.', 'Flussige Oberflache, Benachrichtigungen, automatische Zeugnisse, privater Chat, offizieller Ankundigungskanal und Rollenbereiche.', 'Interface mandeha tsara, fampandrenesana, bulletin automatique, chat prive, canal annonce officiel ary espace isaky ny role.', 'Interfaz fluida, notificaciones, boletines automaticos, chat privado, canal oficial de anuncios y espacios por rol.', 'Interface fluida, notificacoes, boletins automaticos, chat privado, canal oficial de anuncios e espacos por perfil.'],
    ['Securisee & fiable', 'Secure & reliable', 'Sicher & zuverlassig', 'Azo antoka sy maharitra', 'Segura y fiable', 'Segura e confiavel'],
    ['Permissions par module, comptes admin/staff/enseignant/parent, sauvegardes SQL et donnees sous la responsabilite de chaque ecole.', 'Module permissions, admin/staff/teacher/parent accounts, SQL backups and data under each school responsibility.', 'Modulberechtigungen, Admin/Personal/Lehrer/Eltern-Konten, SQL-Sicherungen und Daten in Verantwortung jeder Schule.', 'Permission isaky ny module, kaonty admin/staff/mpampianatra/parent, sauvegarde SQL ary angona andraikitry ny sekoly tsirairay.', 'Permisos por modulo, cuentas admin/personal/profesor/padre, copias SQL y datos bajo responsabilidad de cada escuela.', 'Permissoes por modulo, contas admin/equipe/professor/responsavel, backups SQL e dados sob responsabilidade de cada escola.'],
    ['Ce que Novaskol vous apporte', 'What Novaskol brings you', 'Was Novaskol offers', 'Izay entin i Novaskol ho anao', 'Lo que Novaskol le aporta', 'O que o Novaskol oferece'],
    ['Tableau de bord complet', 'Complete dashboard', 'Vollstandiges Dashboard', 'Dashboard feno', 'Panel completo', 'Painel completo'],
    ['Benefice annuel', 'Annual profit', 'Jahresgewinn', 'Tombony isan-taona', 'Beneficio anual', 'Lucro anual'],
    ['Performance globale', 'Overall performance', 'Gesamtleistung', 'Fahombiazana ankapobeny', 'Rendimiento global', 'Desempenho global'],
    ['Marquer presence', 'Mark attendance', 'Anwesenheit markieren', 'Hanamarika fanatrehana', 'Marcar asistencia', 'Marcar presenca'],
    ['Generer bulletin', 'Generate report card', 'Zeugnis erstellen', 'Hamorona bulletin', 'Generar boletin', 'Gerar boletim'],
    ['Envoyer notification', 'Send notification', 'Benachrichtigung senden', 'Handefa fampandrenesana', 'Enviar notificacion', 'Enviar notificacao'],
    ['Vue rapide de l etablissement', 'Institution quick view', 'Schnellansicht der Einrichtung', 'Fijery haingana ny sekoly', 'Vista rapida del establecimiento', 'Visao rapida da instituicao'],
    ['Acces direct aux espaces les plus utilises.', 'Direct access to the most used spaces.', 'Direkter Zugriff auf die meistgenutzten Bereiche.', 'Fidirana mivantana amin ireo sehatra be mpampiasa.', 'Acceso directo a los espacios mas usados.', 'Acesso direto aos espacos mais usados.'],
    ['Eleves inscrits', 'Registered students', 'Eingeschriebene Schuler', 'Mpianatra voasoratra', 'Alumnos inscritos', 'Alunos inscritos'],
    ['Acceder aux inscriptions', 'Open registrations', 'Anmeldungen offnen', 'Hijery fisoratana', 'Abrir inscripciones', 'Abrir inscricoes'],
    ['Gerer le corps enseignant', 'Manage teaching staff', 'Lehrkorper verwalten', 'Hitantana mpampianatra', 'Gestionar profesores', 'Gerenciar professores'],
    ['Voir le personnel administratif', 'View administrative staff', 'Verwaltungspersonal anzeigen', 'Hijery mpiasa administratif', 'Ver personal administrativo', 'Ver equipe administrativa'],
    ['Suivi des comptes familles', 'Family account tracking', 'Familienkonten verfolgen', 'Fanaraha-maso kaonty fianakaviana', 'Seguimiento de cuentas familiares', 'Acompanhamento de contas familiares'],
    ['Presence aujourd hui', 'Attendance today', 'Anwesenheit heute', 'Fanatrehana androany', 'Asistencia hoy', 'Presenca hoje'],
    ['Consulter la presence du personnel', 'View staff attendance', 'Personal Anwesenheit anzeigen', 'Hijery fanatrehan ny mpiasa', 'Consultar asistencia del personal', 'Consultar presenca da equipe'],
    ['Impayes en cours', 'Current unpaid items', 'Aktuelle Ausstande', 'Tsy voaloa amin izao', 'Impagos actuales', 'Pendencias atuais'],
    ['Ouvrir les details de paiement', 'Open payment details', 'Zahlungsdetails offnen', 'Sokafy antsipiriany fandoavana', 'Abrir detalles de pago', 'Abrir detalhes de pagamento'],
    ['Annee scolaire active', 'Active school year', 'Aktives Schuljahr', 'Taom-pianarana mandeha', 'Ano escolar activo', 'Ano letivo ativo'],
    ['Filtre utilise par le tableau de bord', 'Filter used by the dashboard', 'Vom Dashboard verwendeter Filter', 'Sivana ampiasain ny dashboard', 'Filtro usado por el panel', 'Filtro usado pelo painel'],
    ['Actions rapides', 'Quick actions', 'Schnellaktionen', 'Asa haingana', 'Acciones rapidas', 'Acoes rapidas'],
    ['Les operations quotidiennes les plus importantes.', 'The most important daily operations.', 'Die wichtigsten taglichen Vorgange.', 'Ireo asa andavanandro lehibe indrindra.', 'Las operaciones diarias mas importantes.', 'As operacoes diarias mais importantes.'],
    ['Ajouter eleve', 'Add student', 'Schuler hinzufugen', 'Ampio mpianatra', 'Agregar alumno', 'Adicionar aluno'],
    ['Saisir notes', 'Enter grades', 'Noten eingeben', 'Ampidiro naoty', 'Ingresar notas', 'Inserir notas'],
    ['Prochain evenement', 'Next event', 'Nachstes Ereignis', 'Hetsika manaraka', 'Proximo evento', 'Proximo evento'],
    ['Revenus vs Depenses', 'Income vs expenses', 'Einnahmen vs Ausgaben', 'Vola miditra vs fandaniana', 'Ingresos vs gastos', 'Receitas vs despesas'],
    ['Ajouter un evenement', 'Add event', 'Ereignis hinzufugen', 'Ampio hetsika', 'Agregar evento', 'Adicionar evento'],
    ['Vue 360° : presences, paiements, notes, effectifs, notifications et indicateurs importants.', '360° view: attendance, payments, grades, enrolment, notifications and key indicators.', '360° Ansicht: Anwesenheiten, Zahlungen, Noten, Bestande, Benachrichtigungen und Kennzahlen.', 'Fijery 360°: fanatrehana, fandoavana, naoty, isan olona, fampandrenesana ary tondro lehibe.', 'Vista 360°: asistencias, pagos, notas, efectivos, notificaciones e indicadores clave.', 'Visao 360°: presencas, pagamentos, notas, efetivos, notificacoes e indicadores importantes.'],
    ['Bulletins & notes automatises', 'Automated grades & report cards', 'Automatische Noten & Zeugnisse', 'Naoty sy bulletin automatique', 'Boletines y notas automatizados', 'Boletins e notas automatizados'],
    ['Saisie rapide, calculs instantanes, bulletins trimestriels, annuels, resultats et examens blancs.', 'Fast entry, instant calculations, term and annual report cards, results and mock exams.', 'Schnelle Eingabe, Sofortberechnungen, Trimester- und Jahreszeugnisse, Ergebnisse und Probeprufungen.', 'Fampidirana haingana, kajy avy hatrany, bulletin isam-bolana/isan-taona, valiny ary examen blanc.', 'Captura rapida, calculos instantaneos, boletines trimestrales, anuales, resultados y examenes simulados.', 'Lancamento rapido, calculos instantaneos, boletins trimestrais, anuais, resultados e simulados.'],
    ['Communication integree', 'Integrated communication', 'Integrierte Kommunikation', 'Fifandraisana tafiditra', 'Comunicacion integrada', 'Comunicacao integrada'],
    ['Chat prive, groupes, canal d annonces de l ecole et acces parent encadre.', 'Private chat, groups, school announcement channel and controlled parent access.', 'Privater Chat, Gruppen, Schulankundigungskanal und kontrollierter Elternzugang.', 'Chat prive, vondrona, canal annonce an ny sekoly ary fidiran ny ray aman-dreny voafehy.', 'Chat privado, grupos, canal de anuncios de la escuela y acceso parental controlado.', 'Chat privado, grupos, canal de anuncios da escola e acesso de responsaveis controlado.'],
    ['Comptabilite simplifiee', 'Simplified accounting', 'Vereinfachte Buchhaltung', 'Kaonty notsorina', 'Contabilidad simplificada', 'Contabilidade simplificada'],
    ['Suivi des paiements, factures, recus, revenus, depenses et rapports imprimables.', 'Track payments, invoices, receipts, income, expenses and printable reports.', 'Zahlungen, Rechnungen, Quittungen, Einnahmen, Ausgaben und druckbare Berichte verfolgen.', 'Fanaraha-maso fandoavana, faktiora, rosia, vola miditra, fandaniana ary tatitra azo printena.', 'Seguimiento de pagos, facturas, recibos, ingresos, gastos e informes imprimibles.', 'Acompanhamento de pagamentos, faturas, recibos, receitas, despesas e relatorios imprimiveis.'],
    ['Emploi du temps & agenda', 'Timetable & agenda', 'Stundenplan & Agenda', 'Fandaharam-potoana sy agenda', 'Horario y agenda', 'Horario e agenda'],
    ['Emplois du temps lisibles pour l administration, les enseignants et les parents selon leurs droits.', 'Readable timetables for administration, teachers and parents according to their permissions.', 'Lesbare Stundenplane fur Verwaltung, Lehrkrafte und Eltern entsprechend ihren Rechten.', 'Fandaharam-potoana mazava ho an ny fitantanana, mpampianatra ary ray aman-dreny araka ny zony.', 'Horarios legibles para administracion, profesores y padres segun sus permisos.', 'Horarios claros para administracao, professores e responsaveis conforme suas permissoes.'],
    ['Presences numeriques', 'Digital attendance', 'Digitale Anwesenheit', 'Fanatrehana nomerika', 'Asistencias digitales', 'Presencas digitais'],
    ['Fiches digitales, rapports, alertes absences.', 'Digital sheets, reports and absence alerts.', 'Digitale Listen, Berichte und Abwesenheitswarnungen.', 'Fiche nomerika, tatitra ary fanairana tsy fahatongavana.', 'Fichas digitales, informes y alertas de ausencias.', 'Fichas digitais, relatorios e alertas de ausencia.'],
    ['Aucun message en attente.', 'No pending message.', 'Keine ausstehende Nachricht.', 'Tsy misy hafatra miandry.', 'Ningun mensaje pendiente.', 'Nenhuma mensagem pendente.'],
    ['Couverture actuelle a Antananarivo et environs', 'Current coverage in Antananarivo and surroundings', 'Aktuelle Abdeckung in Antananarivo und Umgebung', 'Faritra voarakotra amin izao ao Antananarivo sy manodidina', 'Cobertura actual en Antananarivo y alrededores', 'Cobertura atual em Antananarivo e arredores'],
    ['Zone couverte : Antananarivo + peripherie (mise a jour reguliere)', 'Covered area: Antananarivo + outskirts (regularly updated)', 'Abgedeckter Bereich: Antananarivo + Umgebung (regelmassig aktualisiert)', 'Faritra voarakotra: Antananarivo sy manodidina (havaozina matetika)', 'Zona cubierta: Antananarivo + periferia (actualizacion regular)', 'Area coberta: Antananarivo + periferia (atualizacao regular)'],
    ['Espace membre', 'Member area', 'Mitgliederbereich', 'Sehatra mpikambana', 'Area de miembros', 'Area de membros'],
    ['Contactez Novaskol', 'Contact Novaskol', 'Novaskol kontaktieren', 'Hifandray amin i Novaskol', 'Contacte Novaskol', 'Contacte o Novaskol'],
    ['Fonctionnalites', 'Features', 'Funktionen', 'Endri-javatra', 'Funciones', 'Funcionalidades'],
    ['A propos', 'About', 'Uber uns', 'Momba', 'Acerca de', 'Sobre'],
    ['Connexion', 'Login', 'Anmeldung', 'Fidirana', 'Conexion', 'Login'],
    ['Une question ? Une demo ? Un partenariat ?', 'A question? A demo? A partnership?', 'Eine Frage? Eine Demo? Eine Partnerschaft?', 'Fanontaniana? Demo? Fiaraha-miasa?', 'Una pregunta? Una demo? Una alianza?', 'Uma pergunta? Uma demo? Uma parceria?'],
    ['Nous repondons sous 24h. En mode local, votre message peut etre prepare puis envoye des qu une connexion est disponible.', 'We reply within 24 hours. In local mode, your message can be prepared and sent once a connection is available.', 'Wir antworten innerhalb von 24 Stunden. Im lokalen Modus kann Ihre Nachricht vorbereitet und gesendet werden, sobald eine Verbindung verfugbar ist.', 'Mamaly ao anatin ny 24 ora izahay. Amin ny fomba local dia azo omanina ny hafatra ary alefa rehefa misy connexion.', 'Respondemos en 24h. En modo local, su mensaje puede prepararse y enviarse cuando haya conexion.', 'Respondemos em 24h. No modo local, sua mensagem pode ser preparada e enviada quando houver conexao.'],
    ['Votre nom', 'Your name', 'Ihr Name', 'Anaranao', 'Su nombre', 'Seu nome'],
    ['Votre email', 'Your email', 'Ihre E-Mail', 'Mailakao', 'Su correo', 'Seu email'],
    ['Sujet', 'Subject', 'Betreff', 'Lohateny', 'Asunto', 'Assunto'],
    ['Votre message...', 'Your message...', 'Ihre Nachricht...', 'Hafatrao...', 'Su mensaje...', 'Sua mensagem...'],
    ['Envoyer', 'Send', 'Senden', 'Alefaso', 'Enviar', 'Enviar'],
    ['Distribution', 'Distribution', 'Verteilung', 'Fizarana', 'Distribucion', 'Distribuicao'],
    ['local, reseau prive ou hebergement web', 'local, private network or web hosting', 'lokal, privates Netzwerk oder Webhosting', 'local, tambajotra manokana na hosting web', 'local, red privada o alojamiento web', 'local, rede privada ou hospedagem web'],
    ['Tous droits reserves', 'All rights reserved', 'Alle Rechte vorbehalten', 'Zo rehetra voatokana', 'Todos los derechos reservados', 'Todos os direitos reservados'],

    ['Lancement Novaskol', 'Starting Novaskol', 'Novaskol startet', 'Mampandeha Novaskol', 'Iniciando Novaskol', 'Iniciando Novaskol'],
    ['Ouverture de Novaskol', 'Opening Novaskol', 'Novaskol wird geoffnet', 'Misokatra Novaskol', 'Abriendo Novaskol', 'Abrindo Novaskol'],
    ['Demarrage du moteur local et preparation de votre espace de travail.', 'Starting the local engine and preparing your workspace.', 'Lokale Engine wird gestartet und Ihr Arbeitsbereich vorbereitet.', 'Mampandeha ny motera local sy manomana ny sehatra fiasanao.', 'Iniciando el motor local y preparando su espacio de trabajo.', 'Iniciando o motor local e preparando seu espaco de trabalho.'],
    ['Etat actuel', 'Current status', 'Aktueller Status', 'Toe-javatra ankehitriny', 'Estado actual', 'Estado atual'],
    ['Preparation en cours...', 'Preparing...', 'Vorbereitung...', 'Manomana...', 'Preparando...', 'Preparando...'],
    ['Version', 'Version', 'Version', 'Dika', 'Version', 'Versao'],
    ['Bonjour', 'Hello', 'Hallo', 'Manao ahoana', 'Hola', 'Ola'],
    ['Voici votre espace utilisateur. Les raccourcis ci-dessous suivent exactement vos permissions.', 'This is your user space. The shortcuts below follow your permissions exactly.', 'Dies ist Ihr Benutzerbereich. Die folgenden Verknupfungen folgen genau Ihren Berechtigungen.', 'Ity ny sehatra mpampiasa anao. Ireo hitsin-dalana eto ambany dia manaraka tsara ny fahazoan-dalanao.', 'Este es su espacio de usuario. Los accesos directos de abajo siguen exactamente sus permisos.', 'Este e o seu espaco de usuario. Os atalhos abaixo seguem exatamente as suas permissoes.'],
    ['Voici votre espace enseignant. Les raccourcis ci-dessous suivent exactement vos permissions.', 'This is your teacher space. The shortcuts below follow your permissions exactly.', 'Dies ist Ihr Lehrerbereich. Die folgenden Verknupfungen folgen genau Ihren Berechtigungen.', 'Ity ny sehatra mpampianatra anao. Ireo hitsin-dalana eto ambany dia manaraka tsara ny fahazoan-dalanao.', 'Este es su espacio docente. Los accesos directos de abajo siguen exactamente sus permisos.', 'Este e o seu espaco de professor. Os atalhos abaixo seguem exatamente as suas permissoes.'],
    ['Voici votre espace staff. Les raccourcis ci-dessous suivent exactement vos permissions.', 'This is your staff space. The shortcuts below follow your permissions exactly.', 'Dies ist Ihr Personalbereich. Die folgenden Verknupfungen folgen genau Ihren Berechtigungen.', 'Ity ny sehatra mpiasa anao. Ireo hitsin-dalana eto ambany dia manaraka tsara ny fahazoan-dalanao.', 'Este es su espacio de personal. Los accesos directos de abajo siguen exactamente sus permisos.', 'Este e o seu espaco da equipe. Os atalhos abaixo seguem exatamente as suas permissoes.'],
    ['Voici votre espace parent. Les raccourcis ci-dessous suivent exactement vos permissions.', 'This is your parent space. The shortcuts below follow your permissions exactly.', 'Dies ist Ihr Elternbereich. Die folgenden Verknupfungen folgen genau Ihren Berechtigungen.', 'Ity ny sehatra ray aman-dreny anao. Ireo hitsin-dalana eto ambany dia manaraka tsara ny fahazoan-dalanao.', 'Este es su espacio de padres. Los accesos directos de abajo siguen exactamente sus permisos.', 'Este e o seu espaco de responsavel. Os atalhos abaixo seguem exatamente as suas permissoes.'],
    ['Modules disponibles', 'Available modules', 'Verfugbare Module', 'Modules misy', 'Modulos disponibles', 'Modulos disponiveis'],
    ['Espace de travail enseignant', 'Teacher workspace', 'Lehrerarbeitsbereich', 'Sehatra fiasan ny mpampianatra', 'Espacio de trabajo docente', 'Espaco de trabalho do professor'],
    ['classe(s), lecon(s), tache(s) ouvertes.', 'class(es), lesson(s), open task(s).', 'Klasse(n), Lektion(en), offene Aufgabe(n).', 'kilasy, lesona, asa misokatra.', 'clase(s), leccion(es), tarea(s) abierta(s).', 'turma(s), licao(oes), tarefa(s) aberta(s).'],
    ['Journal pedagogique', 'Teaching journal', 'Padagogisches Journal', 'Diary pedagogika', 'Diario pedagogico', 'Diario pedagogico'],
    ['Lecons, rubriques, planning et checklist', 'Lessons, sections, planning and checklist', 'Lektionen, Rubriken, Planung und Checkliste', 'Lesona, rubrika, fandaminana ary checklist', 'Lecciones, rubricas, planificacion y lista de control', 'Licoes, rubricas, planejamento e checklist'],
    ['Espace parent', 'Parent space', 'Elternbereich', 'Sehatra ray aman-dreny', 'Espacio de padres', 'Espaco dos responsaveis'],
    ['enfant(s) rattache(s) a ce compte.', 'child(ren) linked to this account.', 'mit diesem Konto verknupfte(s) Kind(er).', 'ankizy mifamatotra amin ity kaonty ity.', 'hijo(s) vinculados a esta cuenta.', 'filho(s) vinculados a esta conta.'],
    ['Notes, presence, paiements et calendrier', 'Grades, attendance, payments and calendar', 'Noten, Anwesenheit, Zahlungen und Kalender', 'Naoty, fanatrehana, fandoavana ary kalandrie', 'Notas, asistencia, pagos y calendario', 'Notas, presenca, pagamentos e calendario'],
    ['Lecture et ecriture', 'Read and write', 'Lesen und Schreiben', 'Mamaky sy manoratra', 'Lectura y escritura', 'Leitura e escrita'],
    ['Lecture seule', 'Read only', 'Nur Lesen', 'Mamaky ihany', 'Solo lectura', 'Somente leitura'],
    ['Aucun module actif pour ce compte. Un administrateur peut ajuster les permissions.', 'No active module for this account. An administrator can adjust permissions.', 'Kein aktives Modul fur dieses Konto. Ein Administrator kann die Berechtigungen anpassen.', 'Tsy misy module mavitrika ho an ity kaonty ity. Afaka manitsy ny fahazoan-dalana ny administrateur.', 'Ningun modulo activo para esta cuenta. Un administrador puede ajustar los permisos.', 'Nenhum modulo ativo para esta conta. Um administrador pode ajustar as permissoes.'],
    ['Dernieres notifications', 'Latest notifications', 'Neueste Benachrichtigungen', 'Fampandrenesana farany', 'Ultimas notificaciones', 'Ultimas notificacoes'],
    ['Aucune notification recente.', 'No recent notification.', 'Keine aktuelle Benachrichtigung.', 'Tsy misy fampandrenesana vao haingana.', 'No hay notificaciones recientes.', 'Sem notificacoes recentes.'],
    ['Classes suivies', 'Tracked classes', 'Betreute Klassen', 'Kilasy arahina', 'Clases seguidas', 'Turmas acompanhadas'],
    ['Poste', 'Position', 'Position', 'Toerana', 'Puesto', 'Cargo'],
    ['Presence du mois', 'Attendance this month', 'Anwesenheit des Monats', 'Fanatrehan ity volana ity', 'Asistencia del mes', 'Presenca do mes'],
    ['Enfants rattaches', 'Linked children', 'Verknupfte Kinder', 'Ankizy mifamatotra', 'Hijos vinculados', 'Filhos vinculados'],
    ['Modules ouverts', 'Open modules', 'Geoffnete Module', 'Modules misokatra', 'Modulos abiertos', 'Modulos abertos'],
    ['Espace enseignant', 'Teacher space', 'Lehrerbereich', 'Sehatra mpampianatra', 'Espacio docente', 'Espaco do professor'],
    ['Journal pedagogique, lecons, rubriques, checklist et planification personnelle.', 'Teaching journal, lessons, sections, checklist and personal planning.', 'Padagogisches Journal, Lektionen, Rubriken, Checkliste und personliche Planung.', 'Diary pedagogika, lesona, rubrika, checklist ary fandaminana manokana.', 'Diario pedagogico, lecciones, rubricas, lista de control y planificacion personal.', 'Diario pedagogico, licoes, rubricas, checklist e planejamento pessoal.'],
    ['lecons suivies', 'tracked lessons', 'verfolgte Lektionen', 'lesona arahina', 'lecciones seguidas', 'licoes acompanhadas'],
    ['lecons terminees', 'completed lessons', 'abgeschlossene Lektionen', 'lesona vita', 'lecciones terminadas', 'licoes concluidas'],
    ['taches ouvertes', 'open tasks', 'offene Aufgaben', 'asa misokatra', 'tareas abiertas', 'tarefas abertas'],
    ['progression moyenne', 'average progress', 'durchschnittlicher Fortschritt', 'fandrosoana antonony', 'progreso medio', 'progresso medio'],
    ['Journal des lecons', 'Lesson journal', 'Lektionsjournal', 'Diary ny lesona', 'Diario de lecciones', 'Diario de licoes'],
    ['Titre de la lecon', 'Lesson title', 'Lektionstitel', 'Lohatenin ny lesona', 'Titulo de la leccion', 'Titulo da licao'],
    ['Rubrique / chapitre', 'Section / chapter', 'Rubrik / Kapitel', 'Rubrika / toko', 'Seccion / capitulo', 'Rubrica / capitulo'],
    ['General', 'General', 'Allgemein', 'Ankapobeny', 'General', 'Geral'],
    ['Date prevue', 'Planned date', 'Geplantes Datum', 'Daty kasaina', 'Fecha prevista', 'Data prevista'],
    ['A preparer', 'To prepare', 'Vorzubereiten', 'Homanina', 'Por preparar', 'A preparar'],
    ['Planifie', 'Planned', 'Geplant', 'Voalamina', 'Planificado', 'Planejado'],
    ['Progression %', 'Progress %', 'Fortschritt %', 'Fandrosoana %', 'Progreso %', 'Progresso %'],
    ['Notes pedagogiques', 'Teaching notes', 'Padagogische Notizen', 'Fanamarihana pedagogika', 'Notas pedagogicas', 'Notas pedagogicas'],
    ['Ajouter au journal', 'Add to journal', 'Zum Journal hinzufugen', 'Ampio ao anaty diary', 'Agregar al diario', 'Adicionar ao diario'],
    ['Sans rubrique', 'No section', 'Ohne Rubrik', 'Tsy misy rubrika', 'Sin seccion', 'Sem rubrica'],
    ['Date libre', 'Open date', 'Freies Datum', 'Daty malalaka', 'Fecha libre', 'Data livre'],
    ['Mettre a jour', 'Update', 'Aktualisieren', 'Havaozy', 'Actualizar', 'Atualizar'],
    ['Supprimer cette lecon ?', 'Delete this lesson?', 'Diese Lektion loschen?', 'Hamafa ity lesona ity?', 'Eliminar esta leccion?', 'Excluir esta licao?'],
    ['Aucune lecon pour ce filtre.', 'No lesson for this filter.', 'Keine Lektion fur diesen Filter.', 'Tsy misy lesona amin ity sivana ity.', 'No hay leccion para este filtro.', 'Nenhuma licao para este filtro.'],
    ['Classes et interventions', 'Classes and interventions', 'Klassen und Einsatze', 'Kilasy sy fidirana', 'Clases e intervenciones', 'Turmas e intervencoes'],
    ['intervention flexible', 'flexible intervention', 'flexibler Einsatz', 'fidirana miovaova', 'intervencion flexible', 'intervencao flexivel'],
    ['classe fixe', 'fixed class', 'feste Klasse', 'kilasy raikitra', 'clase fija', 'turma fixa'],
    ['Aucune classe rattachee.', 'No linked class.', 'Keine verknupfte Klasse.', 'Tsy misy kilasy mifamatotra.', 'No hay clase vinculada.', 'Nenhuma turma vinculada.'],
    ['Checklist', 'Checklist', 'Checkliste', 'Checklist', 'Lista de control', 'Checklist'],
    ['Tache', 'Task', 'Aufgabe', 'Asa', 'Tarea', 'Tarefa'],
    ['Echeance', 'Deadline', 'Frist', 'Fe-potoana', 'Plazo', 'Prazo'],
    ['Priorite', 'Priority', 'Prioritat', 'Laharam-pahamehana', 'Prioridad', 'Prioridade'],
    ['Normale', 'Normal', 'Normal', 'Ara-dalàna', 'Normal', 'Normal'],
    ['Haute', 'High', 'Hoch', 'Avo', 'Alta', 'Alta'],
    ['Basse', 'Low', 'Niedrig', 'Ambany', 'Baja', 'Baixa'],
    ['Sans echeance', 'No deadline', 'Keine Frist', 'Tsy misy fe-potoana', 'Sin plazo', 'Sem prazo'],
    ['Aucune tache.', 'No task.', 'Keine Aufgabe.', 'Tsy misy asa.', 'No hay tareas.', 'Nenhuma tarefa.'],
    ['Terminer / rouvrir', 'Complete / reopen', 'Abschliessen / erneut offnen', 'Vitao / sokafy indray', 'Completar / reabrir', 'Concluir / reabrir'],
    ['Suivi scolaire des enfants', 'Children school follow-up', 'Schulische Ubersicht der Kinder', 'Fanaraha-maso ny fianaran ny ankizy', 'Seguimiento escolar de los hijos', 'Acompanhamento escolar dos filhos'],
    ['Les informations affichees ici sont limitees aux enfants rattaches a votre compte.', 'The information shown here is limited to children linked to your account.', 'Die hier angezeigten Informationen sind auf die mit Ihrem Konto verknupften Kinder beschrankt.', 'Ny fampahalalana aseho eto dia voafetra amin ireo ankizy mifamatotra amin ny kaontinao.', 'La informacion mostrada aqui se limita a los hijos vinculados a su cuenta.', 'As informacoes exibidas aqui se limitam aos filhos vinculados a sua conta.'],
    ['Aucun eleve n est encore rattache a ce compte parent. L administration peut le faire depuis l inscription eleve.', 'No student is linked to this parent account yet. Administration can do it from student registration.', 'Diesem Elternkonto ist noch kein Schuler zugeordnet. Die Verwaltung kann dies in der Schuleranmeldung erledigen.', 'Tsy mbola misy mpianatra mifamatotra amin ity kaonty ray aman-dreny ity. Afaka manao izany avy amin ny fisoratana mpianatra ny fitantanana.', 'Todavia no hay ningun alumno vinculado a esta cuenta de padre. La administracion puede hacerlo desde la inscripcion del alumno.', 'Ainda nao ha nenhum aluno vinculado a esta conta de responsavel. A administracao pode fazer isso pela inscricao do aluno.'],
    ['Lien familial', 'Family link', 'Familienbeziehung', 'Fifandraisana ara-pianakaviana', 'Vinculo familiar', 'Vinculo familiar'],
    ['Presences', 'Attendances', 'Anwesenheiten', 'Fanatrehana', 'Asistencias', 'Presencas'],
    ['Absences', 'Absences', 'Abwesenheiten', 'Tsy fanatrehana', 'Ausencias', 'Ausencias'],
    ['Retards', 'Late arrivals', 'Verspatungen', 'Fahatara', 'Retrasos', 'Atrasos'],
    ['Dernieres notes', 'Latest grades', 'Neueste Noten', 'Naoty farany', 'Ultimas notas', 'Ultimas notas'],
    ['Aucune note disponible.', 'No grade available.', 'Keine Note verfugbar.', 'Tsy misy naoty azo jerena.', 'No hay notas disponibles.', 'Nenhuma nota disponivel.'],
    ['Aucun paiement disponible.', 'No payment available.', 'Keine Zahlung verfugbar.', 'Tsy misy fandoavana azo jerena.', 'No hay pagos disponibles.', 'Nenhum pagamento disponivel.'],
    ['Aucun evenement a venir.', 'No upcoming event.', 'Kein kommendes Ereignis.', 'Tsy misy hetsika ho avy.', 'No hay eventos proximos.', 'Nenhum evento proximo.'],
    ['Guide d utilisation', 'User guide', 'Benutzerhandbuch', 'Torolalana fampiasana', 'Guia de uso', 'Guia de uso'],
    ['Guide complet Novaskol', 'Complete Novaskol guide', 'Vollstandiger Novaskol-Leitfaden', 'Torolalana feno Novaskol', 'Guia completo de Novaskol', 'Guia completo do Novaskol'],
    ['Ce guide accompagne une ecole depuis la premiere configuration jusqu aux operations sensibles. Il suit l ordre logique d utilisation pour eviter les blocages : configurer l ecole creer les classes inscrire les eleves affecter les matieres saisir les notes gerer les paiements puis publier les rapports.', 'This guide supports a school from the first setup to sensitive operations. It follows the logical order of use to avoid blockers: configure the school, create classes, register students, assign subjects, enter grades, manage payments, then publish reports.', 'Dieser Leitfaden begleitet eine Schule von der ersten Einrichtung bis zu sensiblen Vorgangen. Er folgt der logischen Nutzungsreihenfolge, um Blockaden zu vermeiden: Schule konfigurieren, Klassen erstellen, Schuler anmelden, Facher zuweisen, Noten erfassen, Zahlungen verwalten und dann Berichte veroffentlichen.', 'Ity torolalana ity dia manampy sekoly iray manomboka amin ny fanamboarana voalohany ka hatramin ny asa saro-pady. Manaraka ny filaharana lojika izy mba hisorohana ny sakana: amboary ny sekoly, mamorona kilasy, misoratra mpianatra, manendry taranja, mampiditra naoty, mitantana fandoavana ary mamoaka tatitra avy eo.', 'Esta guia acompana a una escuela desde la primera configuracion hasta las operaciones delicadas. Sigue el orden logico de uso para evitar bloqueos: configurar la escuela, crear clases, inscribir alumnos, asignar materias, registrar notas, gestionar pagos y luego publicar informes.', 'Este guia acompanha uma escola desde a primeira configuracao ate as operacoes sensiveis. Segue a ordem logica de uso para evitar bloqueios: configurar a escola, criar turmas, matricular alunos, atribuir disciplinas, lancar notas, gerir pagamentos e depois publicar relatorios.'],
    ['Demarrage', 'Getting started', 'Start', 'Fanombohana', 'Inicio', 'Inicio'],
    ['Eleves et classes', 'Students and classes', 'Schuler und Klassen', 'Mpianatra sy kilasy', 'Alumnos y clases', 'Alunos e turmas'],
    ['Comptabilite', 'Accounting', 'Buchhaltung', 'Kaonty', 'Contabilidad', 'Contabilidade'],
    ['Securite', 'Security', 'Sicherheit', 'Fiarovana', 'Seguridad', 'Seguranca'],
    ['Ouvrir Parametres et completer le nom logo annee scolaire devise et langue.', 'Open Settings and complete the school name, logo, school year, currency and language.', 'Offnen Sie Einstellungen und erganzen Sie Schulname, Logo, Schuljahr, Wahrung und Sprache.', 'Sokafy ny Parametres ary fenoy ny anaran ny sekoly, logo, taom-pianarana, vola ary fiteny.', 'Abra Configuracion y complete el nombre de la escuela, logo, ano escolar, moneda e idioma.', 'Abra Configuracoes e complete o nome da escola, logotipo, ano letivo, moeda e idioma.'],
    ['Creer les comptes staff responsables avant de distribuer les acces.', 'Create responsible staff accounts before distributing access.', 'Erstellen Sie die verantwortlichen Mitarbeiterkonten, bevor Sie Zugange verteilen.', 'Mamorona ny kaonty staff tomponandraikitra alohan ny hizara fidirana.', 'Cree las cuentas del personal responsable antes de distribuir accesos.', 'Crie as contas da equipe responsavel antes de distribuir acessos.'],
    ['Verifier Diagnostic systeme avant une vraie utilisation.', 'Check System diagnostics before real use.', 'Prufen Sie die Systemdiagnose vor dem echten Einsatz.', 'Jereo ny Diagnostic systeme alohan ny fampiasana tena izy.', 'Revise Diagnostico del sistema antes del uso real.', 'Verifique o Diagnostico do sistema antes do uso real.'],
    ['Creer une premiere sauvegarde apres configuration.', 'Create a first backup after configuration.', 'Erstellen Sie nach der Konfiguration eine erste Sicherung.', 'Mamorona sauvegarde voalohany aorian ny fanamboarana.', 'Cree una primera copia de seguridad despues de la configuracion.', 'Crie um primeiro backup apos a configuracao.'],
    ['Creer les classes dans Liste classes.', 'Create classes in Class list.', 'Erstellen Sie Klassen in der Klassenliste.', 'Mamorona kilasy ao amin ny Lisitry ny kilasy.', 'Cree las clases en la Lista de clases.', 'Crie as turmas em Lista de turmas.'],
    ['Ajouter les matieres puis les affecter a chaque classe.', 'Add subjects and then assign them to each class.', 'Fugen Sie Facher hinzu und weisen Sie sie dann jeder Klasse zu.', 'Ampio ny taranja dia tendreo amin ny kilasy tsirairay.', 'Agregue las materias y luego asignelas a cada clase.', 'Adicione as disciplinas e depois atribua-as a cada turma.'],
    ['Inscrire les eleves ou utiliser l importation avec le modele correct.', 'Register students or use the import with the correct template.', 'Melden Sie die Schuler an oder verwenden Sie den Import mit der richtigen Vorlage.', 'Ampidiro ny mpianatra na ampiasao ny import miaraka amin ny modele marina.', 'Inscriba alumnos o use la importacion con la plantilla correcta.', 'Cadastre os alunos ou use a importacao com o modelo correto.'],
    ['Associer les parents dans la fiche eleve si l espace parent est utilise.', 'Link parents in the student record if the parent space is used.', 'Verknupfen Sie Eltern im Schulerprofil, wenn der Elternbereich genutzt wird.', 'Ampifandraiso amin ny ray aman-dreny ny mombamomba ny mpianatra raha ampiasaina ny espace parent.', 'Asocie a los padres en la ficha del alumno si se usa el espacio de padres.', 'Associe os responsaveis na ficha do aluno se o espaco dos responsaveis for usado.'],
    ['Choisir la classe periode et annee scolaire.', 'Choose the class, period and school year.', 'Wahlen Sie Klasse, Zeitraum und Schuljahr.', 'Misafidiana kilasy, fe-potoana ary taom-pianarana.', 'Elija la clase, el periodo y el ano escolar.', 'Escolha a turma, o periodo e o ano letivo.'],
    ['Saisir les notes sur 20. La remarque se remplit automatiquement.', 'Enter grades out of 20. The remark is filled automatically.', 'Geben Sie Noten von 20 ein. Die Bemerkung wird automatisch ausgefullt.', 'Ampidiro amin ny 20 ny naoty. Feno ho azy ny fanamarihana.', 'Ingrese las notas sobre 20. La observacion se completa automaticamente.', 'Insira as notas sobre 20. A observacao e preenchida automaticamente.'],
    ['Verifier les resultats avant d imprimer les bulletins.', 'Check results before printing report cards.', 'Prufen Sie die Ergebnisse, bevor Sie Zeugnisse drucken.', 'Jereo ny valiny alohan ny hanaovana printy ny bulletin.', 'Verifique los resultados antes de imprimir los boletines.', 'Verifique os resultados antes de imprimir os boletins.'],
    ['Utiliser Bulletin annuel en fin d annee.', 'Use Annual report card at the end of the year.', 'Verwenden Sie das Jahreszeugnis am Ende des Jahres.', 'Ampiasao ny Bulletin annuel amin ny faran ny taona.', 'Use Boletin anual al final del ano.', 'Use Boletim anual no fim do ano.'],
    ['Creer les types de paiement et leurs echeances.', 'Create payment types and their due dates.', 'Erstellen Sie Zahlungsarten und deren Falligkeiten.', 'Mamorona karazana fandoavana sy ny daty farany.', 'Cree los tipos de pago y sus vencimientos.', 'Crie os tipos de pagamento e seus vencimentos.'],
    ['Utiliser Details paiement pour voir complet partiel et non paye.', 'Use Payment details to see full, partial and unpaid statuses.', 'Verwenden Sie Zahlungsdetails, um vollstandig, teilweise und unbezahlt zu sehen.', 'Ampiasao ny Details paiement hijerena feno, ampahany ary tsy voaloa.', 'Use Detalles de pago para ver completo, parcial y no pagado.', 'Use Detalhes do pagamento para ver completo, parcial e nao pago.'],
    ['Imprimer recus factures et listes uniquement apres verification.', 'Print receipts, invoices and lists only after verification.', 'Drucken Sie Quittungen, Rechnungen und Listen erst nach der Prufung.', 'Ataovy printy ny rosia, faktiora ary lisitra raha vao voamarina.', 'Imprima recibos, facturas y listas solo despues de verificar.', 'Imprima recibos, faturas e listas somente apos verificar.'],
    ['Les rapports salaires complets sont reserves admin et staff RH Administration.', 'Full salary reports are reserved for admin and HR Administration staff.', 'Vollstandige Gehaltsberichte sind fur Admin und HR-Verwaltungsmitarbeiter reserviert.', 'Natokana ho an ny admin sy ny staff RH Administration ny tatitra karama feno.', 'Los informes salariales completos estan reservados al administrador y al personal de RR.HH. Administracion.', 'Os relatorios salariais completos sao reservados ao admin e a equipe de RH da Administracao.'],
    ['Les presences sont modifiables seulement par les comptes autorises.', 'Attendance can only be edited by authorized accounts.', 'Anwesenheiten konnen nur von autorisierten Konten bearbeitet werden.', 'Ny fanatrehana dia azo ovaina ihany amin ny kaonty nahazo alalana.', 'Las asistencias solo pueden editarse con cuentas autorizadas.', 'As presencas so podem ser editadas por contas autorizadas.'],
    ['Avant mise a jour creer une sauvegarde manuelle.', 'Before updating, create a manual backup.', 'Erstellen Sie vor einer Aktualisierung eine manuelle Sicherung.', 'Alohan ny fanavaozana dia mamorona sauvegarde an-tanana.', 'Antes de actualizar, cree una copia de seguridad manual.', 'Antes de atualizar, crie um backup manual.'],
    ['Copier les fichiers sql sur cle USB ou disque externe.', 'Copy the SQL files to a USB key or external drive.', 'Kopieren Sie die SQL-Dateien auf einen USB-Stick oder ein externes Laufwerk.', 'Adikao amin ny USB na disque externe ny rakitra SQL.', 'Copie los archivos SQL en una llave USB o disco externo.', 'Copie os ficheiros SQL para uma pen USB ou disco externo.'],
    ['Ne jamais partager la sauvegarde d une ecole avec une autre ecole.', 'Never share one school backup with another school.', 'Teilen Sie niemals die Sicherung einer Schule mit einer anderen Schule.', 'Aza zaraina amin ny sekoly hafa mihitsy ny sauvegarde an ny sekoly iray.', 'Nunca comparta la copia de seguridad de una escuela con otra.', 'Nunca compartilhe o backup de uma escola com outra.'],
    ['Ordre recommande pour une nouvelle ecole', 'Recommended order for a new school', 'Empfohlene Reihenfolge fur eine neue Schule', 'Filaharana atolotra ho an ny sekoly vaovao', 'Orden recomendado para una nueva escuela', 'Ordem recomendada para uma nova escola'],
    ['Parametres : ecole devise annee scolaire langue logo.', 'Settings: school, currency, school year, language, logo.', 'Einstellungen: Schule, Wahrung, Schuljahr, Sprache, Logo.', 'Parametres: sekoly, vola, taom-pianarana, fiteny, logo.', 'Configuracion: escuela, moneda, ano escolar, idioma, logo.', 'Configuracoes: escola, moeda, ano letivo, idioma, logotipo.'],
    ['Administration : classes matieres inscriptions eleves parents.', 'Administration: classes, subjects, student registrations, parents.', 'Verwaltung: Klassen, Facher, Schuleranmeldungen, Eltern.', 'Fitantanana: kilasy, taranja, fisoratana mpianatra, ray aman-dreny.', 'Administracion: clases, materias, inscripciones de alumnos, padres.', 'Administracao: turmas, disciplinas, inscricoes de alunos, responsaveis.'],
    ['RH : enseignants staff permissions et roles.', 'HR: teachers, staff, permissions and roles.', 'HR: Lehrkrafte, Personal, Berechtigungen und Rollen.', 'RH: mpampianatra, staff, fahazoan-dalana ary andraikitra.', 'RR.HH.: profesores, personal, permisos y roles.', 'RH: professores, equipe, permissoes e papeis.'],
    ['Pedagogique : emploi du temps calendrier presence eleves.', 'Pedagogy: timetable, calendar, student attendance.', 'Padagogik: Stundenplan, Kalender, Schuleranwesenheit.', 'Pedagojika: fandaharam-potoana, kalandrie, fanatrehan ny mpianatra.', 'Pedagogico: horario, calendario, asistencia de alumnos.', 'Pedagogico: horario, calendario, presenca dos alunos.'],
    ['Professeur : notes bulletins resultats et examen blanc.', 'Teacher: grades, report cards, results and mock exam.', 'Lehrkraft: Noten, Zeugnisse, Ergebnisse und Probeprufung.', 'Mpampianatra: naoty, bulletin, valiny ary examen blanc.', 'Profesor: notas, boletines, resultados y examen simulado.', 'Professor: notas, boletins, resultados e exame simulado.'],
    ['Comptable : frais paiements recus rapports et sauvegarde.', 'Accounting: fees, payments, receipts, reports and backup.', 'Buchhaltung: Gebuhren, Zahlungen, Quittungen, Berichte und Sicherung.', 'Kaonty: sarany, fandoavana, rosia, tatitra ary sauvegarde.', 'Contabilidad: cuotas, pagos, recibos, informes y copias de seguridad.', 'Contabilidade: taxas, pagamentos, recibos, relatorios e backup.'],
    ['Interdiction importante :', 'Important restriction:', 'Wichtiger Hinweis:', 'Fandrarana lehibe:', 'Restriccion importante:', 'Restricao importante:'],
    ['ne jamais supprimer une donnee sensible sans sauvegarde recente. Pour les comptes utilisateurs verifier les permissions avant de donner l acces a un bloc.', 'never delete sensitive data without a recent backup. For user accounts, verify permissions before granting access to a block.', 'Losen Sie sensible Daten niemals ohne aktuelle Sicherung. Prufen Sie bei Benutzerkonten die Berechtigungen, bevor Sie Zugriff auf einen Block gewahren.', 'aza mamafa angona saro-pady raha tsy misy sauvegarde vao haingana. Ho an ny kaonty mpampiasa dia jereo aloha ny permissions alohan ny hanomezana fidirana amin ny bloc.', 'nunca elimine datos sensibles sin una copia de seguridad reciente. Para las cuentas de usuario, verifique los permisos antes de dar acceso a un bloque.', 'nunca exclua dados sensiveis sem um backup recente. Para contas de usuario, verifique as permissoes antes de conceder acesso a um bloco.'],
    ['Parametres de l ecole', 'School settings', 'Schuleinstellungen', 'Fikiran ny sekoly', 'Configuracion de la escuela', 'Configuracoes da escola'],
    ['Informations generales de l ecole', 'General school information', 'Allgemeine Informationen der Schule', 'Fampahalalana ankapoben ny sekoly', 'Informacion general de la escuela', 'Informacoes gerais da escola'],
    ['Code de l ecole', 'School code', 'Schulcode', 'Kaodin ny sekoly', 'Codigo de la escuela', 'Codigo da escola'],
    ['Logo de l ecole', 'School logo', 'Schullogo', 'Logon ny sekoly', 'Logo de la escuela', 'Logotipo da escola'],
    ['Annee scolaire & dates', 'School year & dates', 'Schuljahr & Daten', 'Taom-pianarana sy daty', 'Ano escolar y fechas', 'Ano letivo e datas'],
    ['Date debut', 'Start date', 'Startdatum', 'Daty fanombohana', 'Fecha de inicio', 'Data de inicio'],
    ['Date fin', 'End date', 'Enddatum', 'Daty fiafarana', 'Fecha de fin', 'Data de fim'],
    ['Internationalisation', 'Internationalization', 'Internationalisierung', 'Fampifanarahana iraisam-pirenena', 'Internacionalizacion', 'Internacionalizacao'],
    ['Nom de la devise', 'Currency name', 'Wahrungsname', 'Anaran ny vola', 'Nombre de la moneda', 'Nome da moeda'],
    ['Symbole de la devise', 'Currency symbol', 'Wahrungssymbol', 'Mariky ny vola', 'Simbolo de la moneda', 'Simbolo da moeda'],
    ['Langue principale', 'Main language', 'Hauptsprache', 'Fiteny fototra', 'Idioma principal', 'Idioma principal'],
    ['Bareme des mentions', 'Remark scale', 'Bewertungsskala', 'Mari-pamantarana ny mention', 'Escala de menciones', 'Escala de mencoes'],
    ['Passable minimum', 'Minimum passable', 'Mindestwert ausreichend', 'Farany ambany passable', 'Minimo aceptable', 'Minimo passavel'],
    ['Assez bien minimum', 'Minimum fairly good', 'Mindestwert ziemlich gut', 'Farany ambany assez bien', 'Minimo bastante bien', 'Minimo bastante bom'],
    ['Bien minimum', 'Minimum good', 'Mindestwert gut', 'Farany ambany bien', 'Minimo bien', 'Minimo bom'],
    ['Tres bien minimum', 'Minimum very good', 'Mindestwert sehr gut', 'Farany ambany tres bien', 'Minimo muy bien', 'Minimo muito bom'],
    ['Assurance & administration', 'Insurance & administration', 'Versicherung & Verwaltung', 'Fiantohana sy fitantanana', 'Seguro y administracion', 'Seguro e administracao'],
    ['Code etablissement', 'Institution code', 'Einrichtungscode', 'Kaodin ny sekoly', 'Codigo del establecimiento', 'Codigo da instituicao'],
    ['Telephone etablissement', 'Institution phone', 'Telefon der Einrichtung', 'Findain ny sekoly', 'Telefono del establecimiento', 'Telefone da instituicao'],
    ['Email etablissement', 'Institution email', 'E-Mail der Einrichtung', 'Mailakan ny sekoly', 'Correo del establecimiento', 'Email da instituicao'],
    ['Commentaire', 'Comment', 'Kommentar', 'Fanamarihana', 'Comentario', 'Comentario'],
    ['Activer les notifications par email', 'Enable email notifications', 'E-Mail-Benachrichtigungen aktivieren', 'Alefaso ny fampandrenesana amin ny email', 'Activar notificaciones por correo', 'Ativar notificacoes por email'],
    ['Enregistrer les parametres', 'Save settings', 'Einstellungen speichern', 'Tehirizo ny fikirana', 'Guardar configuracion', 'Salvar configuracoes'],
    ['A propos Novaskol', 'About Novaskol', 'Uber Novaskol', 'Momba an i Novaskol', 'Acerca de Novaskol', 'Sobre o Novaskol'],
    ['Novaskol gestion scolaire moderne', 'Novaskol modern school management', 'Novaskol moderne Schulverwaltung', 'Novaskol fitantanana sekoly maoderina', 'Novaskol gestion escolar moderna', 'Novaskol gestao escolar moderna'],
    ['Cette page resume l etat de l installation la version le mode de fonctionnement les sauvegardes et les informations utiles avant livraison a une ecole.', 'This page summarizes the installation status, version, operating mode, backups and useful information before delivery to a school.', 'Diese Seite fasst Installationsstatus, Version, Betriebsmodus, Sicherungen und nutzliche Informationen vor der Auslieferung an eine Schule zusammen.', 'Ity pejy ity dia mamintina ny satan ny fametrahana, ny dika, ny fomba fiasa, ny sauvegarde ary ny fampahalalana ilaina alohan ny fanaterana amin ny sekoly.', 'Esta pagina resume el estado de la instalacion, la version, el modo de funcionamiento, las copias de seguridad y la informacion util antes de la entrega a una escuela.', 'Esta pagina resume o estado da instalacao, a versao, o modo de funcionamento, os backups e as informacoes uteis antes da entrega a uma escola.'],
    ['Etablissement', 'Institution', 'Einrichtung', 'Sekoly', 'Establecimiento', 'Instituicao'],
    ['Adresse non renseignee', 'Address not provided', 'Adresse nicht angegeben', 'Tsy misy adiresy voasoratra', 'Direccion no indicada', 'Endereco nao informado'],
    ['Telephone non renseigne', 'Phone not provided', 'Telefon nicht angegeben', 'Tsy misy finday voasoratra', 'Telefono no indicado', 'Telefone nao informado'],
    ['Mode installation', 'Installation mode', 'Installationsmodus', 'Fomba fametrahana', 'Modo de instalacion', 'Modo de instalacao'],
    ['Demonstration avec donnees fictives', 'Demo with sample data', 'Demomodus mit Beispieldaten', 'Demo misy angona foronina', 'Demostracion con datos ficticios', 'Demonstracao com dados ficticios'],
    ['Reel / production', 'Live / production', 'Echt / Produktion', 'Tena izy / production', 'Real / produccion', 'Real / producao'],
    ['Mode demo', 'Demo mode', 'Demo-Modus', 'Mode demo', 'Modo demo', 'Modo demo'],
    ['Mode reel', 'Live mode', 'Live-Modus', 'Mode tena izy', 'Modo real', 'Modo real'],
    ['Environnement', 'Environment', 'Umgebung', 'Tontolo iainana', 'Entorno', 'Ambiente'],
    ['Debug actif', 'Debug enabled', 'Debug aktiv', 'Debug mandeha', 'Debug activo', 'Debug ativo'],
    ['Debug desactive', 'Debug disabled', 'Debug deaktiviert', 'Debug maty', 'Debug desactivado', 'Debug desativado'],
    ['Base de donnees', 'Database', 'Datenbank', 'Banky angona', 'Base de datos', 'Base de dados'],
    ['Chaque ecole doit garder sa propre base.', 'Each school must keep its own database.', 'Jede Schule muss ihre eigene Datenbank behalten.', 'Ny sekoly tsirairay dia tsy maintsy mitazona ny banky angona azy.', 'Cada escuela debe conservar su propia base de datos.', 'Cada escola deve manter a sua propria base de dados.'],
    ['Chemin local', 'Local path', 'Lokaler Pfad', 'Lalana eo an-toerana', 'Ruta local', 'Caminho local'],
    ['sauvegarde(s) disponible(s).', 'backup(s) available.', 'verfugbare Sicherung(en).', 'sauvegarde azo ampiasaina.', 'copia(s) de seguridad disponible(s).', 'backup(s) disponivel(is).'],
    ['Derniere :', 'Latest:', 'Letzte:', 'Farany:', 'Ultima:', 'Ultimo:'],
    ['Aucune sauvegarde SQL trouvee.', 'No SQL backup found.', 'Keine SQL-Sicherung gefunden.', 'Tsy misy sauvegarde SQL hita.', 'No se encontro ninguna copia SQL.', 'Nenhum backup SQL encontrado.'],
    ['Dumps guide simple et fichiers de distribution.', 'Dumps, simple guide and distribution files.', 'Dumps, einfache Anleitung und Distributionsdateien.', 'Dump, torolalana tsotra ary rakitra fizarana.', 'Dumps, guia simple y archivos de distribucion.', 'Dumps, guia simples e arquivos de distribuicao.'],
    ['Pret', 'Ready', 'Bereit', 'Vonona', 'Listo', 'Pronto'],
    ['A completer', 'To complete', 'Zu vervollstandigen', 'Mbola hofenoina', 'Por completar', 'A completar'],
    ['Signature', 'Signature', 'Signatur', 'Sonia', 'Firma', 'Assinatura'],
    ['Novaskol est concu pour les ecoles qui veulent gerer leurs donnees localement ou en ligne sans melanger les etablissements.', 'Novaskol is designed for schools that want to manage their data locally or online without mixing institutions.', 'Novaskol ist fur Schulen gedacht, die ihre Daten lokal oder online verwalten wollen, ohne Einrichtungen zu vermischen.', 'Novaskol dia natao ho an ny sekoly te hitantana ny angonany eto an-toerana na an-tserasera nefa tsy afangaro amin ny sekoly hafa.', 'Novaskol esta pensado para las escuelas que quieren gestionar sus datos localmente o en linea sin mezclar establecimientos.', 'Novaskol foi pensado para escolas que querem gerir os seus dados localmente ou online sem misturar instituicoes.'],
    ['Contact / signature projet', 'Project contact / signature', 'Projektkontakt / Signatur', 'Fifandraisana / sonia tetikasa', 'Contacto / firma del proyecto', 'Contato / assinatura do projeto'],
    ['Novaskol Projet de gestion scolaire Laravel pret pour installation locale hebergement et application Windows.', 'Novaskol Laravel school management project, ready for local installation, hosting and a Windows application.', 'Novaskol Laravel Schulverwaltungsprojekt, bereit fur lokale Installation, Hosting und Windows-Anwendung.', 'Tetikasa Novaskol Laravel ho an ny fitantanana sekoly, vonona amin ny fametrahana eto an-toerana, hosting ary application Windows.', 'Proyecto Novaskol de gestion escolar Laravel, listo para instalacion local, alojamiento y aplicacion Windows.', 'Projeto Novaskol de gestao escolar Laravel, pronto para instalacao local, hospedagem e aplicacao Windows.'],
    ['Un systeme de gestion scolaire pour chaque ecole', 'A school management system for every school', 'Ein Schulverwaltungssystem fur jede Schule', 'Rafitra fitantanana sekoly ho an ny sekoly tsirairay', 'Un sistema de gestion escolar para cada escuela', 'Um sistema de gestao escolar para cada escola'],
    ['Novaskol pour chaque ecole', 'Novaskol for every school', 'Novaskol fur jede Schule', 'Novaskol ho an ny sekoly tsirairay', 'Novaskol para cada escuela', 'Novaskol para cada escola'],
    ['Un systeme de gestion scolaire moderne : installable en local partageable ecole par ecole et pret a etre heberge en ligne quand l etablissement le souhaite.', 'A modern school management system: installable locally, shareable school by school, and ready to be hosted online when the institution wants it.', 'Ein modernes Schulverwaltungssystem: lokal installierbar, Schule fur Schule teilbar und bereit fur Online-Hosting, wenn die Einrichtung es wunscht.', 'Rafitra fitantanana sekoly maoderina: azo apetraka eto an-toerana, zaraina isaky ny sekoly, ary vonona hapetraka an-tserasera rehefa ilain ny sekoly.', 'Un sistema de gestion escolar moderno: instalable localmente, compartible escuela por escuela y listo para alojarse en linea cuando la institucion lo desee.', 'Um sistema de gestao escolar moderno: instalavel localmente, compartilhavel escola por escola e pronto para hospedagem online quando a instituicao desejar.'],
    ['Sauvegarde & restauration', 'Backup & restore', 'Sicherung & Wiederherstellung', 'Sauvegarde sy restauration', 'Copia y restauracion', 'Backup e restauracao'],
    ['Appareil principal de l ecole', 'Main school device', 'Hauptgerat der Schule', 'Fitaovana fototry ny sekoly', 'Dispositivo principal de la escuela', 'Dispositivo principal da escola'],
    ['Partager Novaskol sur le meme Wi Fi', 'Share Novaskol on the same Wi-Fi', 'Novaskol im selben WLAN teilen', 'Zarao amin ny Wi-Fi iray i Novaskol', 'Compartir Novaskol en la misma red Wi-Fi', 'Compartilhar o Novaskol na mesma rede Wi-Fi'],
    ['Choisissez un seul appareil principal dans l ecole. C est cet appareil qui garde la base active de Novaskol. Les autres PC tablettes ou telephones ouvrent simplement l adresse locale affichee ci dessous a condition d etre connectes au meme reseau local.', 'Choose a single main device in the school. This device keeps Novaskol main active database. Other PCs, tablets, or phones simply open the local address shown below, as long as they are connected to the same local network.', 'Wahlen Sie in der Schule ein einziges Hauptgerat. Dieses Gerat behalt die aktive Hauptdatenbank von Novaskol. Andere PCs, Tablets oder Telefone offnen einfach die unten angezeigte lokale Adresse, solange sie mit demselben lokalen Netzwerk verbunden sind.', 'Mifidiana fitaovana fototra iray monja ao amin ny sekoly. Io fitaovana io no mitahiry ny base active an i Novaskol. Ny PC, tablette na telefaona hafa dia manokatra fotsiny ny adiresy local aseho etsy ambany raha mifandray amin ilay tambajotra eo an-toerana ihany izy ireo.', 'Elija un solo dispositivo principal en la escuela. Ese dispositivo conserva la base activa principal de Novaskol. Los otros PC, tabletas o telefonos solo deben abrir la direccion local mostrada abajo, siempre que esten conectados a la misma red local.', 'Escolha um unico dispositivo principal na escola. Esse dispositivo mantem a base ativa principal do Novaskol. Os outros PCs, tablets ou telefones apenas abrem o endereco local mostrado abaixo, desde que estejam ligados a mesma rede local.'],
    ['Pret a partager', 'Ready to share', 'Bereit zum Teilen', 'Vonona hifampizara', 'Listo para compartir', 'Pronto para compartilhar'],
    ['A verifier', 'Needs checking', 'Zu prufen', 'Mila jerena', 'Por verificar', 'A verificar'],
    ['Important :', 'Important:', 'Wichtig:', 'Zava-dehibe :', 'Importante:', 'Importante:'],
    ['l appareil principal doit rester allume pendant l utilisation des autres appareils.', 'the main device must stay powered on while other devices are in use.', 'das Hauptgerat muss wahrend der Nutzung der anderen Gerate eingeschaltet bleiben.', 'tsy maintsy mijanona mirehitra ny fitaovana fototra mandritra ny fampiasana ireo fitaovana hafa.', 'el dispositivo principal debe permanecer encendido mientras se utilizan los otros dispositivos.', 'o dispositivo principal deve permanecer ligado durante o uso dos outros dispositivos.'],
    ['Nom de l appareil principal', 'Main device name', 'Name des Hauptgerats', 'Anaran ny fitaovana fototra', 'Nombre del dispositivo principal', 'Nome do dispositivo principal'],
    ['Bureau administration', 'Administration desk', 'Verwaltungsburo', 'Biraon ny fitantanana', 'Oficina administrativa', 'Mesa da administracao'],
    ['Scanner ce QR code', 'Scan this QR code', 'Diesen QR-Code scannen', 'Scanio ity QR code ity', 'Escanear este codigo QR', 'Digitalize este codigo QR'],
    ['Le telephone ou le portable doit etre connecte au meme Wi Fi que l appareil principal pour ouvrir Novaskol.', 'The phone or laptop must be connected to the same Wi-Fi as the main device to open Novaskol.', 'Das Telefon oder der Laptop muss mit demselben WLAN wie das Hauptgerat verbunden sein, um Novaskol zu offnen.', 'Tsy maintsy mifandray amin ilay Wi-Fi iray amin ny fitaovana fototra ny telefaona na ny solosaina finday raha hanokatra an i Novaskol.', 'El telefono o portatil debe estar conectado al mismo Wi-Fi que el dispositivo principal para abrir Novaskol.', 'O telefone ou portatil deve estar ligado ao mesmo Wi-Fi que o dispositivo principal para abrir o Novaskol.'],
    ['Nom technique', 'Technical name', 'Technischer Name', 'Anarana teknika', 'Nombre tecnico', 'Nome tecnico'],
    ['Adresse locale', 'Local address', 'Lokale Adresse', 'Adiresy local', 'Direccion local', 'Endereco local'],
    ['Adresse locale copiee.', 'Local address copied.', 'Lokale Adresse kopiert.', 'Voadika ny adiresy local.', 'Direccion local copiada.', 'Endereco local copiado.'],
    ['Port local', 'Local port', 'Lokaler Port', 'Port local', 'Puerto local', 'Porta local'],
    ['Nom visible pour l equipe de l ecole.', 'Name visible to the school team.', 'Name fur das Schulteam sichtbar.', 'Anarana hitan ny ekipan ny sekoly.', 'Nombre visible para el equipo de la escuela.', 'Nome visivel para a equipa da escola.'],
    ['Nom Windows detecte automatiquement.', 'Windows name detected automatically.', 'Automatisch erkannter Windows-Name.', 'Anaran Windows hita ho azy.', 'Nombre de Windows detectado automaticamente.', 'Nome do Windows detectado automaticamente.'],
    ['Les autres appareils utilisent ce meme port dans l adresse locale.', 'Other devices use this same port in the local address.', 'Andere Gerate verwenden denselben Port in der lokalen Adresse.', 'Io port io ihany no ampiasain ny fitaovana hafa ao amin ny adiresy local.', 'Los otros dispositivos usan este mismo puerto en la direccion local.', 'Os outros dispositivos usam esta mesma porta no endereco local.'],
    ['Copier l adresse', 'Copy address', 'Adresse kopieren', 'Adikao ny adiresy', 'Copiar direccion', 'Copiar endereco'],
    ['Tester l ouverture', 'Test opening', 'Offnung testen', 'Andramo ny fanokafana', 'Probar apertura', 'Testar abertura'],
    ['Connecter un autre appareil', 'Connect another device', 'Weiteres Gerat verbinden', 'Ampifandraiso fitaovana hafa', 'Conectar otro dispositivo', 'Conectar outro dispositivo'],
    ['Ouvrir Novaskol sur l appareil principal de l ecole.', 'Open Novaskol on the main school device.', 'Offnen Sie Novaskol auf dem Hauptgerat der Schule.', 'Sokafy amin ny fitaovana fototry ny sekoly i Novaskol.', 'Abra Novaskol en el dispositivo principal de la escuela.', 'Abra o Novaskol no dispositivo principal da escola.'],
    ['Connecter l autre appareil au meme Wi Fi ou au meme routeur local.', 'Connect the other device to the same Wi-Fi or the same local router.', 'Verbinden Sie das andere Gerat mit demselben WLAN oder demselben lokalen Router.', 'Ampifandraiso amin ilay Wi-Fi iray ihany na router local iray ihany ilay fitaovana hafa.', 'Conecte el otro dispositivo al mismo Wi-Fi o al mismo router local.', 'Ligue o outro dispositivo ao mesmo Wi-Fi ou ao mesmo router local.'],
    ['Ouvrir l adresse locale affichee plus haut ou scanner le QR code.', 'Open the local address shown above or scan the QR code.', 'Offnen Sie die oben angezeigte lokale Adresse oder scannen Sie den QR-Code.', 'Sokafy ilay adiresy local aseho etsy ambony na scanio ny QR code.', 'Abra la direccion local mostrada arriba o escanee el codigo QR.', 'Abra o endereco local mostrado acima ou digitalize o QR code.'],
    ['Se connecter avec un compte autorise de l ecole.', 'Sign in with an authorised school account.', 'Melden Sie sich mit einem autorisierten Schulkonto an.', 'Midira amin ny kaonty nahazo alalana an ny sekoly.', 'Inicie sesion con una cuenta autorizada de la escuela.', 'Entre com uma conta autorizada da escola.'],
    ['Si un appareil n arrive pas a se connecter', 'If a device cannot connect', 'Wenn sich ein Gerat nicht verbinden kann', 'Raha tsy afaka mifandray ny fitaovana iray', 'Si un dispositivo no puede conectarse', 'Se um dispositivo nao conseguir ligar-se'],
    ['Verifier que l appareil principal est toujours allume.', 'Check that the main device is still powered on.', 'Prufen Sie, ob das Hauptgerat noch eingeschaltet ist.', 'Hamarino fa mbola mirehitra ny fitaovana fototra.', 'Verifique que el dispositivo principal siga encendido.', 'Verifique se o dispositivo principal ainda esta ligado.'],
    ['Verifier que les deux appareils sont sur le meme reseau local.', 'Check that both devices are on the same local network.', 'Prufen Sie, ob beide Gerate im selben lokalen Netzwerk sind.', 'Hamarino fa ao amin ilay tambajotra eo an-toerana iray ihany ireo fitaovana roa ireo.', 'Verifique que ambos dispositivos esten en la misma red local.', 'Verifique se os dois dispositivos estao na mesma rede local.'],
    ['Autoriser Novaskol / PHP dans le pare feu Windows si besoin.', 'Allow Novaskol / PHP through Windows Firewall if needed.', 'Erlauben Sie Novaskol / PHP bei Bedarf in der Windows-Firewall.', 'Avelao handalo amin ny pare-feu Windows i Novaskol / PHP raha ilaina.', 'Permita Novaskol / PHP en el Firewall de Windows si es necesario.', 'Permita o Novaskol / PHP na Firewall do Windows se necessario.'],
    ['Relancer Novaskol si l adresse locale ne repond plus.', 'Restart Novaskol if the local address no longer responds.', 'Starten Sie Novaskol neu, wenn die lokale Adresse nicht mehr antwortet.', 'Alefaso indray i Novaskol raha tsy mamaly intsony ny adiresy local.', 'Reinicie Novaskol si la direccion local ya no responde.', 'Reinicie o Novaskol se o endereco local deixar de responder.'],
    ['Choisir un seul PC principal pour garder la base active de Novaskol.', 'Choose a single main PC to keep the active Novaskol database.', 'Wahlen Sie einen einzigen Haupt-PC, um die aktive Novaskol-Datenbank zu behalten.', 'Misafidiana PC fototra iray monja hitahiry ny base active an i Novaskol.', 'Elija un solo PC principal para conservar la base activa de Novaskol.', 'Escolha um unico PC principal para manter a base ativa do Novaskol.'],
    ['Ouvrir le module Appareil principal pour recuperer l adresse locale de l ecole.', 'Open the Main device module to get the school local address.', 'Offnen Sie das Modul Hauptgerat, um die lokale Adresse der Schule zu erhalten.', 'Sokafy ny module Fitaovana fototra hahazoana ny adiresy local an ny sekoly.', 'Abra el modulo Dispositivo principal para obtener la direccion local de la escuela.', 'Abra o modulo Dispositivo principal para obter o endereco local da escola.'],
    ['Connecter les autres PC ou telephones au meme Wi Fi.', 'Connect the other PCs or phones to the same Wi-Fi.', 'Verbinden Sie die anderen PCs oder Telefone mit demselben WLAN.', 'Ampifandraiso amin ilay Wi-Fi iray ihany ny PC na telefaona hafa.', 'Conecte los otros PC o telefonos al mismo Wi-Fi.', 'Ligue os outros PCs ou telefones ao mesmo Wi-Fi.'],
    ['Scanner le QR code ou ouvrir l adresse locale depuis l autre appareil.', 'Scan the QR code or open the local address from the other device.', 'Scannen Sie den QR-Code oder offnen Sie die lokale Adresse vom anderen Gerat aus.', 'Scanio ny QR code na sokafy amin ny fitaovana hafa ilay adiresy local.', 'Escanee el codigo QR o abra la direccion local desde el otro dispositivo.', 'Digitalize o QR code ou abra o endereco local no outro dispositivo.'],
    ['Appareil principal : partager Novaskol sur le meme Wi Fi pour les autres appareils de l ecole.', 'Main device: share Novaskol on the same Wi-Fi for the other school devices.', 'Hauptgerat: Teilen Sie Novaskol im selben WLAN fur die anderen Schulgerate.', 'Fitaovana fototra: zarao amin ilay Wi-Fi iray i Novaskol ho an ny fitaovana hafa ao amin ny sekoly.', 'Dispositivo principal: comparta Novaskol en el mismo Wi-Fi para los otros dispositivos de la escuela.', 'Dispositivo principal: partilhe o Novaskol no mesmo Wi-Fi para os outros dispositivos da escola.'],
    ['Gerer les versions de la base de donnees', 'Manage database versions', 'Datenbankversionen verwalten', 'Hitantana ny dikan ny banky angona', 'Gestionar versiones de la base de datos', 'Gerenciar versoes da base de dados'],
    ['Ce module permet de creer une sauvegarde manuelle telecharger une version ou revenir directement vers une sauvegarde precise. Avant chaque restauration Novaskol cree automatiquement une nouvelle sauvegarde de l etat courant pour eviter toute perte definitive.', 'This module lets you create a manual backup, download a version, or return directly to a specific backup. Before each restore, Novaskol automatically creates a new backup of the current state to avoid any permanent loss.', 'Dieses Modul ermoglicht das Erstellen einer manuellen Sicherung, das Herunterladen einer Version oder die direkte Ruckkehr zu einer bestimmten Sicherung. Vor jeder Wiederherstellung erstellt Novaskol automatisch eine neue Sicherung des aktuellen Zustands, um jeden endgultigen Verlust zu vermeiden.', 'Ity module ity dia mamela anao hamorona sauvegarde an-tanana, hisintona dika iray, na hiverina mivantana amin ny sauvegarde voafaritra. Alohan ny restauration tsirairay dia mamorona sauvegarde vaovao ho azy amin ny toetry ny angona ankehitriny i Novaskol mba hisorohana ny fahaverezana maharitra.', 'Este modulo permite crear una copia manual, descargar una version o volver directamente a una copia especifica. Antes de cada restauracion, Novaskol crea automaticamente una nueva copia del estado actual para evitar cualquier perdida definitiva.', 'Este modulo permite criar um backup manual, descarregar uma versao ou voltar diretamente para um backup especifico. Antes de cada restauracao, o Novaskol cria automaticamente um novo backup do estado atual para evitar qualquer perda definitiva.'],
    ['Conseil :', 'Tip:', 'Tipp:', 'Torohevitra :', 'Consejo:', 'Dica:'],
    ['avant une grosse modification ou avant de restaurer une ancienne version gardez toujours au moins une sauvegarde recente externe sur cle USB disque ou cloud de l ecole.', 'before a major change or before restoring an older version, always keep at least one recent external backup on a USB key, disk or the school cloud.', 'Bewahren Sie vor einer grossen Anderung oder vor der Wiederherstellung einer alteren Version immer mindestens eine aktuelle externe Sicherung auf USB-Stick, Laufwerk oder in der Schul-Cloud auf.', 'alohan ny fanovana lehibe na alohan ny hamerenana dika taloha dia tehirizo hatrany farafahakeliny sauvegarde ivelany vao haingana amin ny USB, kapila na cloud an ny sekoly.', 'antes de una gran modificacion o antes de restaurar una version antigua, conserve siempre al menos una copia externa reciente en USB, disco o nube de la escuela.', 'antes de uma grande alteracao ou antes de restaurar uma versao antiga, mantenha sempre pelo menos um backup externo recente em USB, disco ou nuvem da escola.'],
    ['Etat actuel', 'Current status', 'Aktueller Status', 'Toetry ny rafitra ankehitriny', 'Estado actual', 'Estado atual'],
    ['Sauvegardes disponibles', 'Available backups', 'Verfugbare Sicherungen', 'Sauvegarde misy', 'Copias disponibles', 'Backups disponiveis'],
    ['Derniere creation', 'Latest creation', 'Letzte Erstellung', 'Famoronana farany', 'Ultima creacion', 'Ultima criacao'],
    ['Historique des sauvegardes', 'Backup history', 'Sicherungsverlauf', 'Tantaran ny sauvegarde', 'Historial de copias', 'Historico de backups'],
    ['Fichier', 'File', 'Datei', 'Rakitra', 'Archivo', 'Arquivo'],
    ['Taille', 'Size', 'Grosse', 'Habe', 'Tamano', 'Tamanho'],
    ['Etat', 'State', 'Status', 'Toetra', 'Estado', 'Estado'],
    ['Auto avant restauration', 'Auto before restore', 'Automatisch vor Wiederherstellung', 'Auto alohan ny restauration', 'Auto antes de restaurar', 'Auto antes da restauracao'],
    ['Sauvegarde normale', 'Standard backup', 'Normale Sicherung', 'Sauvegarde mahazatra', 'Copia normal', 'Backup normal'],
    ['Creer une sauvegarde maintenant', 'Create a backup now', 'Jetzt Sicherung erstellen', 'Mamorona sauvegarde izao', 'Crear una copia ahora', 'Criar um backup agora'],
    ['Revenir a cette base', 'Return to this database', 'Zu dieser Datenbank zuruckkehren', 'Hiverina amin ity base ity', 'Volver a esta base', 'Voltar para esta base'],
    ['Restaurer cette base ?', 'Restore this database?', 'Diese Datenbank wiederherstellen?', 'Hamerina ity base ity?', 'Restaurar esta base?', 'Restaurar esta base?'],
    ['Novaskol reviendra a cette sauvegarde. Une sauvegarde automatique de l etat actuel sera creee avant la restauration.', 'Novaskol will return to this backup. An automatic backup of the current state will be created before the restore.', 'Novaskol kehrt zu dieser Sicherung zuruck. Vor der Wiederherstellung wird automatisch eine Sicherung des aktuellen Zustands erstellt.', 'Hiverina amin ity sauvegarde ity i Novaskol. Hamorona sauvegarde automatique amin ny toetry ny rafitra ankehitriny izy alohan ny restauration.', 'Novaskol volvera a esta copia. Antes de restaurar, se creara automaticamente una copia del estado actual.', 'O Novaskol voltara para este backup. Antes da restauracao, sera criado automaticamente um backup do estado atual.'],
    ['Aucune sauvegarde pour le moment.', 'No backup yet.', 'Noch keine Sicherung.', 'Tsy mbola misy sauvegarde.', 'Aun no hay copias.', 'Ainda nao ha backups.'],
  ].forEach((row) => add(...row));

  const skipSelector = 'script,style,noscript,template,code,pre,textarea,canvas,svg,[data-no-i18n],.no-i18n,.no-translate,.select2-search__field';
  const translatableAttrs = ['placeholder', 'title', 'aria-label', 'data-confirm-title', 'data-confirm-text'];

  function currentLanguage() {
    const saved = localStorage.getItem('novaskol-language');
    const initial = window.NOVASKOL_INITIAL_LANGUAGE || document.documentElement.lang || 'fr';
    return languages[saved] ? saved : (languages[initial] ? initial : 'fr');
  }

  function lookup(original, lang) {
    if (!original || lang === 'fr') return original;
    const key = normalize(original);
    if (dict[lang]?.[key]) return dict[lang][key];

    const fragments = [
      ['Tous droits reserves', 'All rights reserved', 'Alle Rechte vorbehalten', 'Zo rehetra voatokana', 'Todos los derechos reservados', 'Todos os direitos reservados'],
      ['Tous droits reserves.', 'All rights reserved.', 'Alle Rechte vorbehalten.', 'Zo rehetra voatokana.', 'Todos los derechos reservados.', 'Todos os direitos reservados.'],
      ['Ecole', 'School', 'Schule', 'Sekoly', 'Escuela', 'Escola'],
      ['Mon espace', 'My space', 'Mein Bereich', 'Sehatra manokana', 'Mi espacio', 'Meu espaco'],
      ['Espace enseignant', 'Teacher space', 'Lehrerbereich', 'Sehatra mpampianatra', 'Espacio docente', 'Espaco do professor'],
      ['Espace parent', 'Parent space', 'Elternbereich', 'Sehatra ray aman-dreny', 'Espacio parent', 'Espaco do responsavel'],
      ['Bonjour', 'Hello', 'Hallo', 'Manao ahoana', 'Hola', 'Ola'],
      ['enseignant', 'teacher', 'Lehrer', 'mpampianatra', 'docente', 'professor'],
      ['staff', 'staff', 'Personal', 'mpiasa', 'personal', 'equipe'],
      ['parent', 'parent', 'Elternteil', 'ray aman-dreny', 'padre', 'responsavel'],
      ['utilisateur', 'user', 'Benutzer', 'mpampiasa', 'usuario', 'usuario'],
    ];
    let result = original;
    fragments.forEach((row) => {
      const source = row[0];
      const target = row[['fr', 'en', 'de', 'mg', 'es', 'pt'].indexOf(lang)] || source;
      if (source === 'Ecole' && /Ecole\s+[A-Z]/.test(result)) return;
      result = result.replace(new RegExp(source.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi'), target);
    });
    result = result.replace(/©\s*(\d{4})\s+(.+?)\.\s+Tous droits reserves\.?$/i, (_m, year, school) => {
      const endings = {
        en: `© ${year} ${school}. All rights reserved.`,
        de: `© ${year} ${school}. Alle Rechte vorbehalten.`,
        mg: `© ${year} ${school}. Zo rehetra voatokana.`,
        es: `© ${year} ${school}. Todos los derechos reservados.`,
        pt: `© ${year} ${school}. Todos os direitos reservados.`,
      };
      return endings[lang] || _m;
    });
    result = result.replace(/^Bonjour\s+(.+)$/i, (_m, name) => {
      const starts = { en: 'Hello', de: 'Hallo', mg: 'Manao ahoana', es: 'Hola', pt: 'Ola' };
      return `${starts[lang] || 'Bonjour'} ${name}`;
    });
    result = result.replace(/^Voici votre espace (utilisateur|enseignant|staff|parent)\.\s+Les raccourcis ci-dessous suivent exactement vos permissions\.$/i, (_m, role) => {
      const sentences = {
        en: {
          utilisateur: 'This is your user space. The shortcuts below follow your permissions exactly.',
          enseignant: 'This is your teacher space. The shortcuts below follow your permissions exactly.',
          staff: 'This is your staff space. The shortcuts below follow your permissions exactly.',
          parent: 'This is your parent space. The shortcuts below follow your permissions exactly.',
        },
        de: {
          utilisateur: 'Dies ist Ihr Benutzerbereich. Die folgenden Verknupfungen folgen genau Ihren Berechtigungen.',
          enseignant: 'Dies ist Ihr Lehrerbereich. Die folgenden Verknupfungen folgen genau Ihren Berechtigungen.',
          staff: 'Dies ist Ihr Personalbereich. Die folgenden Verknupfungen folgen genau Ihren Berechtigungen.',
          parent: 'Dies ist Ihr Elternbereich. Die folgenden Verknupfungen folgen genau Ihren Berechtigungen.',
        },
        mg: {
          utilisateur: 'Ity ny sehatra mpampiasa anao. Ireo hitsin-dalana eto ambany dia manaraka tsara ny fahazoan-dalanao.',
          enseignant: 'Ity ny sehatra mpampianatra anao. Ireo hitsin-dalana eto ambany dia manaraka tsara ny fahazoan-dalanao.',
          staff: 'Ity ny sehatra mpiasa anao. Ireo hitsin-dalana eto ambany dia manaraka tsara ny fahazoan-dalanao.',
          parent: 'Ity ny sehatra ray aman-dreny anao. Ireo hitsin-dalana eto ambany dia manaraka tsara ny fahazoan-dalanao.',
        },
        es: {
          utilisateur: 'Este es su espacio de usuario. Los accesos directos de abajo siguen exactamente sus permisos.',
          enseignant: 'Este es su espacio docente. Los accesos directos de abajo siguen exactamente sus permisos.',
          staff: 'Este es su espacio de personal. Los accesos directos de abajo siguen exactement sus permisos.',
          parent: 'Este es su espacio de padres. Los accesos directos de abajo siguen exactement sus permisos.',
        },
        pt: {
          utilisateur: 'Este e o seu espaco de usuario. Os atalhos abaixo seguem exatamente as suas permissoes.',
          enseignant: 'Este e o seu espaco de professor. Os atalhos abaixo seguem exactement as suas permissoes.',
          staff: 'Este e o seu espaco da equipe. Os atalhos abaixo seguem exactement as suas permissoes.',
          parent: 'Este e o seu espaco de responsavel. Os atalhos abaixo seguem exactement as suas permissoes.',
        },
      };
      return sentences[lang]?.[role] || _m;
    });
    result = result.replace(/^(\d+)\s+enfant\(s\)\s+rattache\(s\)\s+a ce compte\.?$/i, (_m, count) => {
      const endings = {
        en: `${count} child(ren) linked to this account.`,
        de: `${count} mit diesem Konto verknupfte(s) Kind(er).`,
        mg: `${count} ankizy mifamatotra amin ity kaonty ity.`,
        es: `${count} hijo(s) vinculados a esta cuenta.`,
        pt: `${count} filho(s) vinculados a esta conta.`,
      };
      return endings[lang] || _m;
    });
    return result;
  }

  function shouldTranslateText(text) {
    const clean = String(text || '').trim();
    if (!clean || clean.length > 650) return false;
    if (/^[\d\s.,:;/%+()#-]+$/.test(clean)) return false;
    if (/^[\w.+-]+@[\w.-]+\.[a-z]{2,}$/i.test(clean)) return false;
    return true;
  }

  function translateTextNode(node, lang) {
    if (!node.parentElement || node.parentElement.closest(skipSelector)) return;
    if (!shouldTranslateText(node.textContent)) return;
    if (!node.__novaskolI18nOriginal) {
      node.__novaskolI18nOriginal = node.textContent;
    }
    const original = node.__novaskolI18nOriginal;
    const leading = (original.match(/^\s*/) || [''])[0];
    const trailing = (original.match(/\s*$/) || [''])[0];
    const core = original.trim();
    const translated = lookup(core, lang);
    node.textContent = leading + translated + trailing;
  }

  function translateAttributes(el, lang) {
    if (el.closest('script,style,noscript,template,code,pre,canvas,svg,[data-no-i18n],.no-i18n,.no-translate')) return;
    translatableAttrs.forEach((attr) => {
      if (!el.hasAttribute(attr)) return;
      const originalAttr = `i18nOriginal${attr.replace(/[^a-z0-9]/gi, '')}`;
      if (!el.dataset[originalAttr]) {
        el.dataset[originalAttr] = el.getAttribute(attr) || '';
      }
      el.setAttribute(attr, lookup(el.dataset[originalAttr], lang));
    });
    if (el.matches?.('input[type="submit"],input[type="button"],input[type="reset"]')) {
      if (!el.dataset.i18nOriginalValue) {
        el.dataset.i18nOriginalValue = el.value || '';
      }
      if (el.dataset.i18nOriginalValue) {
        el.value = lookup(el.dataset.i18nOriginalValue, lang);
      }
    }
  }

  function translateElementFallback(el, lang) {
    if (!el || !el.tagName || el.closest(skipSelector)) return;

    const childElements = Array.from(el.children || []);
    const onlyInlineChildren = childElements.every((child) => ['I', 'IMG', 'SVG', 'BR'].includes(child.tagName));
    const textNodes = Array.from(el.childNodes || []).filter((node) => node.nodeType === Node.TEXT_NODE);
    const originalText = textNodes.map((node) => node.textContent || '').join(' ').replace(/\s+/g, ' ').trim();

    if (!shouldTranslateText(originalText)) return;
    if (childElements.length && !onlyInlineChildren) return;

    if (!el.dataset.i18nContentOriginal) {
      el.dataset.i18nContentOriginal = originalText;
    }

    const translated = lookup(el.dataset.i18nContentOriginal, lang);
    if (!translated || translated === originalText) return;

    if (!textNodes.length) {
      el.textContent = translated;
      return;
    }

    let replaced = false;
    textNodes.forEach((node, index) => {
      const originalNode = node.__novaskolI18nOriginal || node.textContent || '';
      const leading = (originalNode.match(/^\s*/) || [''])[0];
      const trailing = (originalNode.match(/\s*$/) || [''])[0];
      if (!replaced) {
        node.textContent = leading + translated + trailing;
        replaced = true;
      } else if (index > 0) {
        node.textContent = '';
      }
    });
  }

  function forceTranslateCommonElements(scope, lang) {
    if (!scope || !scope.querySelectorAll) return;

    scope.querySelectorAll('[placeholder],[title],[aria-label],[data-confirm-title],[data-confirm-text]').forEach((el) => {
      translateAttributes(el, lang);
    });

    scope.querySelectorAll('button,option,label,h1,h2,h3,h4,h5,h6,p,a,span,small,strong,div,legend').forEach((el) => {
      translateElementFallback(el, lang);
    });
  }

  function walk(root, lang) {
    const scope = root || document.body;
    if (!scope) return;

    if (scope.nodeType === Node.ELEMENT_NODE) {
      translateAttributes(scope, lang);
    }

    const tree = document.createTreeWalker(scope, NodeFilter.SHOW_TEXT, {
      acceptNode(node) {
        if (!node.parentElement || node.parentElement.closest(skipSelector)) {
          return NodeFilter.FILTER_REJECT;
        }
        return shouldTranslateText(node.textContent) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
      },
    });

    const nodes = [];
    while (tree.nextNode()) nodes.push(tree.currentNode);
    nodes.forEach((node) => translateTextNode(node, lang));

    if (scope.querySelectorAll) {
      scope.querySelectorAll('*').forEach((el) => translateAttributes(el, lang));
    }

    forceTranslateCommonElements(scope, lang);

    document.querySelectorAll('[data-lang-option]').forEach((button) => {
      button.classList.toggle('active', button.dataset.langCode === lang);
    });
  }

  let observer;
  let applying = false;

  function apply(lang) {
    const selected = languages[lang] ? lang : currentLanguage();
    localStorage.setItem('novaskol-language', selected);
    document.documentElement.lang = selected;
    applying = true;
    walk(document.body, selected);
    if (document.title) {
      if (!document.documentElement.dataset.i18nTitleOriginal) {
        document.documentElement.dataset.i18nTitleOriginal = document.title;
      }
      document.title = lookup(document.documentElement.dataset.i18nTitleOriginal, selected);
    }
    applying = false;
    document.dispatchEvent(new CustomEvent('novaskol:language-changed', { detail: { lang: selected } }));
  }

  function installObserver() {
    if (observer || !document.body) return;
    observer = new MutationObserver((mutations) => {
      if (applying) return;
      const lang = currentLanguage();
      mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
          if (node.nodeType === Node.ELEMENT_NODE || node.nodeType === Node.TEXT_NODE) {
            walk(node.nodeType === Node.TEXT_NODE ? node.parentElement : node, lang);
          }
        });
      });
    });
    observer.observe(document.body, { childList: true, subtree: true });
  }

  function patchSweetAlert() {
    if (!window.Swal || window.Swal.__novaskolI18nPatched) return;
    const originalFire = window.Swal.fire.bind(window.Swal);
    window.Swal.fire = function (...args) {
      const lang = currentLanguage();
      if (args[0] && typeof args[0] === 'object') {
        ['title', 'text', 'confirmButtonText', 'cancelButtonText', 'denyButtonText'].forEach((key) => {
          if (typeof args[0][key] === 'string') args[0][key] = lookup(args[0][key], lang);
        });
      } else if (typeof args[0] === 'string') {
        args[0] = lookup(args[0], lang);
      }
      return originalFire(...args);
    };
    window.Swal.__novaskolI18nPatched = true;
  }

  window.NovaskolI18n = {
    languages,
    apply,
    translate: (text, lang = currentLanguage()) => lookup(text, lang),
    current: currentLanguage,
  };

  window.novaskolSetLanguage = function (lang) {
    apply(lang);
    const labels = {
      fr: 'Langue active : Francais',
      en: 'Active language: English',
      de: 'Aktive Sprache: Deutsch',
      mg: 'Fiteny voafidy: Malagasy',
      es: 'Idioma activo: Espanol',
      pt: 'Idioma ativo: Portugues',
    };
    if (window.Swal) {
      window.Swal.fire({
        toast: true,
        position: 'top-end',
        timer: 1600,
        showConfirmButton: false,
        icon: 'success',
        title: labels[lang] || labels.fr,
      });
    }
  };

  document.addEventListener('DOMContentLoaded', () => {
    patchSweetAlert();
    installObserver();
    apply(currentLanguage());
  });
  window.addEventListener('load', () => {
    patchSweetAlert();
    apply(currentLanguage());
  });
})();
