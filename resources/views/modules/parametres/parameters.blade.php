<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parametres</title>
    <link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
    @include('modules.professeur.bulletin.partials.styles')
    @include('modules.parametres.partials.styles')
</head>
<body>
@include('modules.professeur.bulletin.partials.shell')
<header><div class="header-left"><button class="burger-menu" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button><button id="fullscreen-btn" onclick="toggleFullscreen()"><i class="fa fa-expand"></i></button></div><div class="header-center"><i class="fa fa-cog"></i> Parametres de l'ecole</div></header>
<main>
    <section class="settings-panel">
        <div class="settings-section">
            <h2>Documents importants</h2>
            <div class="settings-grid">
                <a class="field" href="{{ route('modules.guide-utilisation') }}" style="text-decoration:none;color:inherit;display:block;">
                    <label>Guide d'utilisation</label>
                    <div>Procedure complete pour bien utiliser Novaskol dans l'ordre.</div>
                </a>
                <a class="field" href="{{ route('modules.reseau-local') }}" style="text-decoration:none;color:inherit;display:block;">
                    <label>Reseau local</label>
                    <div>Adresse locale, QR code et partage sur le meme reseau.</div>
                </a>
                <a class="field" href="{{ route('modules.sauvegardes') }}" style="text-decoration:none;color:inherit;display:block;">
                    <label>Sauvegarde & restauration</label>
                    <div>Creer, telecharger, restaurer ou revenir a une base precise.</div>
                </a>
                <a class="field" href="{{ route('modules.parametres.privacy') }}" style="text-decoration:none;color:inherit;display:block;">
                    <label>Politique de confidentialite</label>
                    <div>Protection des donnees de l'ecole, des parents, des enseignants et des eleves.</div>
                </a>
                <a class="field" href="{{ route('modules.parametres.terms') }}" style="text-decoration:none;color:inherit;display:block;">
                    <label>Conditions d'utilisation</label>
                    <div>Regles d'usage, responsabilites et bonnes pratiques pour l'etablissement.</div>
                </a>
            </div>
        </div>
        <form method="POST" action="{{ route('modules.parametres.save') }}" enctype="multipart/form-data">
            @csrf
            <div class="settings-section">
                <h2>Informations generales de l'ecole</h2>
                <div class="settings-grid">
                    <div class="field"><label>Nom de l'ecole</label><input name="nom_ecole" value="{{ old('nom_ecole', $params['nom_ecole'] ?? ($ecole->nom ?? '')) }}"></div>
                    <div class="field"><label>Code de l'ecole</label><input name="code_ecole" value="{{ old('code_ecole', $params['code_ecole'] ?? '') }}"></div>
                    <div class="field"><label>Adresse</label><input name="adresse_ecole" value="{{ old('adresse_ecole', $params['adresse_ecole'] ?? '') }}"></div>
                    <div class="field"><label>Telephone</label><input name="telephone_ecole" value="{{ old('telephone_ecole', $params['telephone_ecole'] ?? '') }}"></div>
                    <div class="field"><label>Email</label><input type="email" name="email_ecole" value="{{ old('email_ecole', $params['email_ecole'] ?? '') }}"></div>
                    <div class="field"><label>Logo de l'ecole</label><input type="file" name="logo_ecole" accept="image/*">@if(($params['logo_ecole'] ?? '') || ($ecole->logo ?? ''))<img class="logo-preview" src="{{ asset('legacy/images/'.basename($params['logo_ecole'] ?? ($ecole->logo ?? 'novaskol.png'))) }}" alt="Logo actuel">@endif</div>
                </div>
            </div>
            <div class="settings-section">
                <h2>Annee scolaire & dates</h2>
                <div class="settings-grid">
                    <div class="field"><label>Annee scolaire</label><input name="annee_scolaire" placeholder="Ex: 2025-2026" value="{{ old('annee_scolaire', $params['annee_scolaire'] ?? '') }}"></div>
                    <div class="field"><label>Date debut</label><input type="date" name="date_debut" value="{{ old('date_debut', $params['date_debut'] ?? '') }}"></div>
                    <div class="field"><label>Date fin</label><input type="date" name="date_fin" value="{{ old('date_fin', $params['date_fin'] ?? '') }}"></div>
                </div>
            </div>
            <div class="settings-section">
                <h2>Internationalisation</h2>
                <div class="settings-grid">
                    <div class="field"><label>Nom de la devise</label><input name="devise_nom" placeholder="Ex: Ariary, Euro, Dollar" value="{{ old('devise_nom', $params['devise_nom'] ?? 'Ariary') }}"></div>
                    <div class="field"><label>Symbole de la devise</label><input name="devise_symbole" placeholder="Ex: Ar, EUR, $, CFA" value="{{ old('devise_symbole', $params['devise_symbole'] ?? 'Ar') }}"></div>
                    <div class="field">
                        <label>Langue principale</label>
                        <select name="langue_interface">
                            <option value="fr" @selected(old('langue_interface', $params['langue_interface'] ?? 'fr') === 'fr')>Francais</option>
                            <option value="en" @selected(old('langue_interface', $params['langue_interface'] ?? 'fr') === 'en')>English</option>
                            <option value="de" @selected(old('langue_interface', $params['langue_interface'] ?? 'fr') === 'de')>Deutsch</option>
                            <option value="mg" @selected(old('langue_interface', $params['langue_interface'] ?? 'fr') === 'mg')>Malagasy</option>
                            <option value="es" @selected(old('langue_interface', $params['langue_interface'] ?? 'fr') === 'es')>Espanol</option>
                            <option value="pt" @selected(old('langue_interface', $params['langue_interface'] ?? 'fr') === 'pt')>Portugues</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="settings-section">
                <h2>Bareme des mentions</h2>
                <div class="settings-grid">
                    <div class="field"><label>Passable minimum</label><input type="number" step="0.01" min="0" max="20" name="mention_passable" value="{{ old('mention_passable', $params['mention_passable'] ?? '10') }}"></div>
                    <div class="field"><label>Assez bien minimum</label><input type="number" step="0.01" min="0" max="20" name="mention_assez_bien" value="{{ old('mention_assez_bien', $params['mention_assez_bien'] ?? '12') }}"></div>
                    <div class="field"><label>Bien minimum</label><input type="number" step="0.01" min="0" max="20" name="mention_bien" value="{{ old('mention_bien', $params['mention_bien'] ?? '14') }}"></div>
                    <div class="field"><label>Tres bien minimum</label><input type="number" step="0.01" min="0" max="20" name="mention_tres_bien" value="{{ old('mention_tres_bien', $params['mention_tres_bien'] ?? '16') }}"></div>
                </div>
            </div>
            <div class="settings-section">
                <h2>Assurance & administration</h2>
                <div class="settings-grid">
                    <div class="field"><label>DREN</label><input name="dren" value="{{ old('dren', $params['dren'] ?? '') }}"></div>
                    <div class="field"><label>CISCO</label><input name="cisco" value="{{ old('cisco', $params['cisco'] ?? '') }}"></div>
                    <div class="field"><label>ZAP</label><input name="zap" value="{{ old('zap', $params['zap'] ?? '') }}"></div>
                    <div class="field"><label>Code etablissement</label><input name="code_etablissement" value="{{ old('code_etablissement', $params['code_etablissement'] ?? '') }}"></div>
                    <div class="field"><label>Telephone etablissement</label><input name="tel_etablissement" value="{{ old('tel_etablissement', $params['tel_etablissement'] ?? '') }}"></div>
                    <div class="field"><label>Email etablissement</label><input type="email" name="mail_etablissement" value="{{ old('mail_etablissement', $params['mail_etablissement'] ?? '') }}"></div>
                    <div class="field"><label>Commentaire</label><textarea name="nb_comment" rows="3">{{ old('nb_comment', $params['nb_comment'] ?? '') }}</textarea></div>
                    <label class="check-field"><input type="checkbox" name="notifications_mail" value="1" @checked(old('notifications_mail', $params['notifications_mail'] ?? '1') === '1')> Activer les notifications par email</label>
                </div>
            </div>
            <div style="text-align:center"><button class="kaly"><i class="fa fa-save"></i> Enregistrer les parametres</button></div>
        </form>
    </section>
    <footer>&copy; {{ date('Y') }} {{ $ecole->nom ?? 'Ecole' }}. Tous droits reserves.</footer>
</main>
<script>function toggleSidebar(){document.getElementById('sidebar').classList.toggle('active')}function toggleSub(e){const n=e.nextElementSibling;if(n)n.style.display=n.style.display==='none'?'block':'none'}function toggleFullscreen(){document.fullscreenElement?document.exitFullscreen():document.documentElement.requestFullscreen()}</script>
</body>
</html>
