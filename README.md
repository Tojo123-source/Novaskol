# Novaskol

Novaskol est un systeme de gestion scolaire Laravel pense pour deux usages :

- une version locale/offline pour les ecoles qui veulent garder leurs donnees sur leur propre PC ;
- une version hebergee pour les ecoles qui veulent utiliser leur propre domaine.

Chaque ecole garde sa propre base de donnees. Le projet ne depend plus de l'ancien code PHP pour fonctionner, sauf pour certains assets et dossiers historiques places dans `public/legacy`.

## Demarrage local

```bash
composer install
copy .env.local.example .env
php artisan key:generate
php artisan storage:link
```

Ensuite :

1. Creer une base MySQL vide.
2. Importer `database/distribution/dump_empty.sql`.
3. Configurer `.env`.
4. Ouvrir `/installation`.
5. Choisir `Base vide` ou `Mode demo`.

Guide complet : `docs/INSTALLATION_LOCALE.md`.

## Hebergement

Pour une ecole en ligne :

```bash
composer install --no-dev --optimize-autoloader
copy .env.production.example .env
php artisan key:generate
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Guide complet : `docs/HEBERGEMENT.md`.

## Distribution

Regenerer les dumps :

```bash
php artisan novaskol:make-distribution-dumps --demo
```

Verifier les fichiers indispensables :

```bash
php artisan novaskol:release-check
```

Guide produit : `docs/DISTRIBUTION.md`.

## Diagnostic

Apres installation, un administrateur peut ouvrir :

```text
/diagnostic-systeme
```

Cette page verifie les points essentiels : base de donnees, stockage, dumps, mode debug, cle application et dossiers utiles.
