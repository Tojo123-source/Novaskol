# 🔨 INSTRUCTIONS DE REBUILD COMPLET

**Status:** Les anciennes versions ont des problèmes et doivent être reconstruites.

**Solution:** Scripts de rebuild complet fournis.

---

## 🚀 COMMENT FAIRE

### Option 1: Rebuild Tout en Une Fois (RECOMMANDÉ)

**Fichier à utiliser:** `REBUILD-ALL-CLEAN.bat`

**Instructions:**
1. **Double-cliquer** sur `REBUILD-ALL-CLEAN.bat` dans le répertoire racine
2. **Attendre** 15-20 minutes pour que les deux builds se terminent
3. **Vérifier** que les deux fichiers .exe apparaissent:
   - `storage/app/desktop-dist/Novaskol-Setup-1.0.5-x64.exe`
   - `storage/app/desktop-connecte-dist/Novaskol-Connecte-Setup-0.2.0-x64.exe`

**C'est tout!**

---

### Option 2: Rebuild Séparé (Si vous préférez)

#### Rebuild Desktop Seul
```batch
REBUILD-DESKTOP-CLEAN.bat
```
Attend 10 minutes, génère Desktop installer.

#### Rebuild Connecte Seul
```batch
REBUILD-CONNECTE-CLEAN.bat
```
Attend 10 minutes, génère Connecte installer.

---

## 🔍 CE QUE LES SCRIPTS FONT

### REBUILD-DESKTOP-CLEAN.bat

1. ✅ **Supprime** l'ancien dossier `storage/app/desktop-dist/`
2. ✅ **Supprime** `desktop/node_modules` (vieux dépendances)
3. ✅ **Supprime** `desktop/package-lock.json`
4. ✅ **Installe** dépendances fraîches avec `npm install`
5. ✅ **Construit** application avec `npm run dist`
6. ✅ **Génère** `Novaskol-Setup-1.0.5-x64.exe` NEUF
7. ✅ **Vérifie** que le fichier existe et affiche sa taille

### REBUILD-CONNECTE-CLEAN.bat

Même processus pour Connecte v0.2.0

### REBUILD-ALL-CLEAN.bat

Lance les deux scripts dans l'ordre.

---

## ⏱️ TEMPS ESTIMÉ

| Étape | Temps | Notes |
|-------|-------|-------|
| Desktop build | 10 min | npm install (2-3 min) + build (5-10 min) |
| Connecte build | 10 min | npm install (2-3 min) + build (5-10 min) |
| **TOTAL** | **20 min** | Parallel possible mais scripts séquentiels |

---

## ✅ VÉRIFICATION

Après que le script se termine:

```
Vérifier que ces fichiers EXISTENT:
✅ storage/app/desktop-dist/Novaskol-Setup-1.0.5-x64.exe
✅ storage/app/desktop-connecte-dist/Novaskol-Connecte-Setup-0.2.0-x64.exe
```

Si les fichiers existent et la date est aujourd'hui → **BUILD RÉUSSI!** ✅

---

## 🚨 SI ERREUR

### "npm: command not found"
**Solution:** Installer Node.js depuis https://nodejs.org/

### "Build failed"
**Solution:** 
1. Ouvrir Command Prompt comme administrateur
2. Naviguer au dossier du projet
3. Exécuter le script à nouveau
4. Vérifier les messages d'erreur

### "Old directory could not be deleted"
**Solution:** 
1. Ouvrir l'explorateur de fichiers
2. Aller à `storage/app/`
3. Supprimer manuellement `desktop-dist` et `desktop-connecte-dist`
4. Relancer le script

---

## 💡 NOTES IMPORTANTES

- **Supression complète:** Les anciens fichiers sont COMPLÈTEMENT supprimés avant rebuild
- **Dépendances fraîches:** `node_modules` est supprimé et réinstallé (= builds plus lents mais plus propres)
- **Vérification automatique:** Le script vérifie que le .exe final existe
- **Migrations incluses:** La correction de la base de données (migration) est AUTOMATIQUEMENT incluse

---

## 🎯 RÉSUMÉ

```
AVANT (Erronné):
❌ Anciennes versions avec migration cassée
❌ node_modules potentiellement corrompu
❌ Build non à jour

APRÈS (Correct):
✅ Versions fraîches construites
✅ Dépendances clean réinstallées
✅ Migration correcte incluse
✅ Applications opérationnelles
```

---

**Prêt?**

**→ Double-cliquez sur: `REBUILD-ALL-CLEAN.bat`**

Attendez 15-20 minutes et vous aurez vos deux applications correctes!

---

*Instructions de Rebuild Complet*  
*Novaskol Desktop v1.0.5 + Connecte v0.2.0*  
*Date: 2026-05-19*
