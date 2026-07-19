<div class="form-section">
    <h3>Informations Personnelles</h3>
    <div class="form-grid">
        @if ($includeMatricule)
            <div>
                <label for="{{ $prefix }}matricule">Matricule :</label>
                <input type="text" name="matricule" id="{{ $prefix }}matricule" required>
            </div>
        @endif
        <div>
            <label for="{{ $prefix }}prenom">Prenom :</label>
            <input type="text" name="prenom" id="{{ $prefix }}prenom" required>
        </div>
        <div>
            <label for="{{ $prefix }}nom">Nom :</label>
            <input type="text" name="nom" id="{{ $prefix }}nom" required>
        </div>
        <div>
            <label for="{{ $prefix }}date_naissance">Date de naissance :</label>
            <input type="date" name="date_naissance" id="{{ $prefix }}date_naissance" required>
        </div>
        <div>
            <label for="{{ $prefix }}lieu_naissance">Lieu de naissance :</label>
            <input type="text" name="lieu_naissance" id="{{ $prefix }}lieu_naissance" required>
        </div>
        <div>
            <label for="{{ $prefix }}numero_acte">N Acte :</label>
            <input type="text" name="numero_acte" id="{{ $prefix }}numero_acte" required>
        </div>
        <div>
            <label for="{{ $prefix }}genre">Genre :</label>
            <select name="genre" id="{{ $prefix }}genre" required>
                <option value="">-- Choisir --</option>
                <option value="G">Garcon</option>
                <option value="F">Fille</option>
            </select>
        </div>
        <div>
            <label for="{{ $prefix }}statut">Statut :</label>
            <select name="statut" id="{{ $prefix }}statut" required>
                <option value="">-- Choisir --</option>
                <option value="nouveau">Nouveau</option>
                <option value="passant">Passant</option>
                <option value="redoublant">Redoublant</option>
            </select>
        </div>
        <div>
            <label for="{{ $prefix }}est_handicap">Handicap :</label>
            <input type="checkbox" name="est_handicap" id="{{ $prefix }}est_handicap">
        </div>
        <div>
            <label for="{{ $prefix }}photo">Photo :</label>
            <div class="photo-container">
                <input type="file" name="photo" id="{{ $prefix }}photo" accept="image/*">
            </div>
            @if ($includeMatricule)
                <label for="{{ $prefix }}supprimer_photo">Supprimer la photo :</label>
                <input type="checkbox" name="supprimer_photo" id="{{ $prefix }}supprimer_photo" value="1">
            @endif
        </div>
    </div>
</div>

<div class="form-section">
    <h3>Compte eleve en ligne</h3>
    <div class="form-grid">
        <div>
            <label for="{{ $prefix }}email">Email de connexion eleve :</label>
            <input type="email" name="email" id="{{ $prefix }}email" placeholder="eleve@email.com">
        </div>
        <div>
            <label for="{{ $prefix }}mot_de_passe">Mot de passe eleve :</label>
            <input type="password" name="mot_de_passe" id="{{ $prefix }}mot_de_passe" placeholder="{{ $includeMatricule ? 'Vide = garder' : 'Min 6 caracteres' }}">
        </div>
    </div>
</div>

<div class="form-section parent-account-section">
    <h3>Compte parent en ligne</h3>
    <div class="form-grid">
        <div>
            <label for="{{ $prefix }}creer_compte_parent">Creer/lier un compte parent :</label>
            <input type="checkbox" name="creer_compte_parent" id="{{ $prefix }}creer_compte_parent" value="1">
        </div>
        <div>
            <label for="{{ $prefix }}parent_lien">Responsable :</label>
            <select name="parent_lien" id="{{ $prefix }}parent_lien">
                <option value="pere">Pere</option>
                <option value="mere">Mere</option>
                <option value="tuteur">Tuteur</option>
                <option value="parent">Autre parent</option>
            </select>
        </div>
        <div>
            <label for="{{ $prefix }}parent_nom_compte">Nom du compte :</label>
            <input type="text" name="parent_nom_compte" id="{{ $prefix }}parent_nom_compte" placeholder="Laisser vide pour utiliser le nom du pere/mere">
        </div>
        <div>
            <label for="{{ $prefix }}parent_email_compte">Email de connexion :</label>
            <input type="email" name="parent_email_compte" id="{{ $prefix }}parent_email_compte" placeholder="parent@email.com">
        </div>
        <div>
            <label for="{{ $prefix }}parent_mot_de_passe">Mot de passe provisoire :</label>
            <input type="password" name="parent_mot_de_passe" id="{{ $prefix }}parent_mot_de_passe" placeholder="{{ $includeMatricule ? 'Vide = garder le mot de passe actuel' : 'Minimum 6 caracteres' }}">
        </div>
    </div>
</div>

<div class="form-section">
    <h3>Informations Scolaires</h3>
    <div class="form-grid">
        <div>
            <label for="{{ $prefix }}classe_id">Classe :</label>
            <select name="classe_id" id="{{ $prefix }}classe_id" required>
                <option value="">-- Choisir --</option>
                @foreach ($classes as $classe)
                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="{{ $prefix }}annee_scolaire">Annee scolaire :</label>
            <input type="text" name="annee_scolaire" id="{{ $prefix }}annee_scolaire" value="{{ $currentYear }}" required>
        </div>
        <div>
            <label for="{{ $prefix }}ecole_ancienne">Ecole precedente :</label>
            <input type="text" name="ecole_ancienne" id="{{ $prefix }}ecole_ancienne">
        </div>
    </div>
</div>

<div class="form-section">
    <h3>Informations de Contact</h3>
    <div class="form-grid">
        <div>
            <label for="{{ $prefix }}adresse">Adresse :</label>
            <input type="text" name="adresse" id="{{ $prefix }}adresse" required>
        </div>
        <div>
            <label for="{{ $prefix }}fonkotany">Fonkotany :</label>
            <input type="text" name="fonkotany" id="{{ $prefix }}fonkotany" required>
        </div>
        <div>
            <label for="{{ $prefix }}commune">Commune :</label>
            <input type="text" name="commune" id="{{ $prefix }}commune" required>
        </div>
        <div>
            <label for="{{ $prefix }}telephone">Telephone :</label>
            <input type="text" name="telephone" id="{{ $prefix }}telephone" required>
        </div>
        <div>
            <label for="{{ $prefix }}distance_domicile">Distance domicile > 5 km :</label>
            <input type="checkbox" name="distance_domicile" id="{{ $prefix }}distance_domicile">
        </div>
    </div>
</div>

<div class="form-section">
    <h3>Informations des Parents</h3>
    <div class="form-grid">
        <div>
            <label for="{{ $prefix }}nom_pere">Nom du pere :</label>
            <input type="text" name="nom_pere" id="{{ $prefix }}nom_pere">
        </div>
        <div>
            <label for="{{ $prefix }}telephone_pere">Telephone du pere :</label>
            <input type="text" name="telephone_pere" id="{{ $prefix }}telephone_pere">
        </div>
        <div>
            <label for="{{ $prefix }}profession_pere">Profession du pere :</label>
            <input type="text" name="profession_pere" id="{{ $prefix }}profession_pere">
        </div>
        <div>
            <label for="{{ $prefix }}adresse_pere">Adresse du pere :</label>
            <input type="text" name="adresse_pere" id="{{ $prefix }}adresse_pere">
        </div>
        <div>
            <label for="{{ $prefix }}nom_mere">Nom de la mere :</label>
            <input type="text" name="nom_mere" id="{{ $prefix }}nom_mere">
        </div>
        <div>
            <label for="{{ $prefix }}telephone_mere">Telephone de la mere :</label>
            <input type="text" name="telephone_mere" id="{{ $prefix }}telephone_mere">
        </div>
        <div>
            <label for="{{ $prefix }}profession_mere">Profession de la mere :</label>
            <input type="text" name="profession_mere" id="{{ $prefix }}profession_mere">
        </div>
        <div>
            <label for="{{ $prefix }}adresse_mere">Adresse de la mere :</label>
            <input type="text" name="adresse_mere" id="{{ $prefix }}adresse_mere">
        </div>
    </div>
</div>
