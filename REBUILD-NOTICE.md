# ⚠️ CORRECTION DE VERSIONING - REBUILD NÉCESSAIRE

**Découvert:** Les versions construites précédemment (Novaskol v1.0.5 et v0.2.0) contenaient des problèmes

**Décision:** Reconstruire complètement avec dépendances fraîches

**Status:** Scripts de rebuild fournis - Prêt à exécuter

---

## 🔴 PROBLÈME

Les fichiers .exe existants dans:
- `storage/app/desktop-dist/Novaskol-Setup-1.0.5-x64.exe` 
- `storage/app/desktop-connecte-dist/Novaskol-Connecte-Setup-0.2.0-x64.exe`

Étaient basés sur des builds potentiellement incorrects avec:
- ❌ node_modules potentiellement cassé
- ❌ Dépendances obsolètes
- ❌ Cache de build ancien

---

## 🟢 SOLUTION

Trois scripts de rebuild fournis:

### 1. REBUILD-DESKTOP-CLEAN.bat
```
✅ Supprime l'ancienne version
✅ Nettoie node_modules et package-lock.json
✅ Réinstalle dépendances fraîches
✅ Rebuild complet
✅ Génère nouveau Novaskol-Setup-1.0.5-x64.exe
```

### 2. REBUILD-CONNECTE-CLEAN.bat
```
✅ Supprime l'ancienne version
✅ Nettoie node_modules et package-lock.json
✅ Réinstalle dépendances fraîches
✅ Rebuild complet
✅ Génère nouveau Novaskol-Connecte-Setup-0.2.0-x64.exe
```

### 3. REBUILD-ALL-CLEAN.bat (RECOMMANDÉ)
```
✅ Lance les deux rebuilds en séquence
✅ Génère les deux versions correctes
✅ Temps total: 15-20 minutes
```

---

## 📋 PROCHAINES ÉTAPES

1. **Double-cliquez** sur: `REBUILD-ALL-CLEAN.bat`

2. **Attendez** 15-20 minutes

3. **Vérifiez** que ces fichiers existent:
   - `storage/app/desktop-dist/Novaskol-Setup-1.0.5-x64.exe`
   - `storage/app/desktop-connecte-dist/Novaskol-Connecte-Setup-0.2.0-x64.exe`

4. **Testez** les nouvelles versions (optionnel)

5. **Distribuez** les fichiers .exe corrigés

---

## 🎯 RÉSUMÉ

| Aspect | Avant | Après |
|--------|-------|-------|
| **Desktop .exe** | ❌ Potentiellement erronée | ✅ Fraîchement construite |
| **Connecte .exe** | ❌ Potentiellement erronée | ✅ Fraîchement construite |
| **node_modules** | ❌ Potentiellement corrompu | ✅ Complètement réinstallé |
| **Dépendances** | ❌ Obsolètes/cachées | ✅ Fraîches et nettoyées |
| **Build** | ❌ Cache ancien | ✅ Rebuild complet |

---

**Fichier à exécuter:** `REBUILD-ALL-CLEAN.bat`

**Pour instructions détaillées:** Voir `REBUILD-INSTRUCTIONS.md`

---

*Correction de Versioning - Novaskol Applications*  
*Date: 2026-05-19*  
*Status: Prêt pour rebuild*
