# Livre de Soutenance

## Conception et réalisation d'une application de gestion scolaire hors-ligne avec Laravel, Electron et SQLite

---

**Présenté par :**
Tojo Nambinina RANDRIAMIFALY

**Encadré par :**
[Nom de l'encadrant]

**Parcours :** Communication en Audiovisuelle et Numérique (CAN)
**Spécialisation :** Développement Web (après L3)
**Année universitaire :** 2025-2026

---

## Remerciements

En premier lieu, nous remercions Dieu tout-puissant de nous avoir donné la force, la patience et la détermination nécessaires pour mener à bien ce projet.

Nous tenons à exprimer notre profonde gratitude à notre encadrant pour ses conseils précieux, sa disponibilité et son accompagnement tout au long de ce travail. Sa confiance et ses orientations éclairées ont grandement contribué à la réussite de ce projet.

Nous remercions chaleureusement les membres du jury qui nous font l'honneur d'évaluer ce travail.

Nos sincères remerciements vont également à toutes les personnes qui ont participé de près ou de loin à la réalisation de ce projet, en particulier les enseignants et le personnel administratif des établissements scolaires qui nous ont accordé de leur temps pour les entretiens et les tests.

Enfin, nous exprimons notre reconnaissance à nos familles et à nos proches pour leur soutien indéfectible et leurs encouragements tout au long de notre parcours universitaire.

---

## Dédicaces

Je dédie ce modeste travail à :

Mes chers parents, pour leur amour inconditionnel, leurs sacrifices et leur soutien permanent tout au long de mes études.

Mes frères et sœurs, pour leur encouragement et leur présence à mes côtés.

Tous mes amis et camarades de promotion, pour les moments partagés et l'entraide mutuelle.

Tous ceux qui ont contribué, de près ou de loin, à la réalisation de ce projet.

---

## Sommaire

**Introduction générale**

**Chapitre I : État de l'art et technologies utilisées**
1. Introduction
2. Architecture logicielle
3. Architectures distribuées
4. Modèle Client/Serveur
5. Internet et le Web
6. Technologies de développement web
7. Systèmes de Gestion de Base de Données
8. Applications de bureau hybrides
9. Conclusion

**Chapitre II : Architecture et technologies de Novaskol**
1. Introduction
2. Présentation du projet Novaskol
3. Architecture globale du système
4. Technologies et outils utilisés
5. Architecture détaillée de la base de données
6. Modules et fonctionnalités
7. Conclusion

**Chapitre III : Analyse et conception**
1. Introduction
2. Analyse des besoins
3. Identification des acteurs
4. Diagrammes de cas d'utilisation
5. Diagrammes de séquence
6. Diagrammes d'activité
7. Modèle conceptuel de données
8. Conclusion

**Chapitre IV : Réalisation**
1. Introduction
2. Environnement de développement
3. Implémentation du backend Laravel
4. Implémentation du frontend
5. Application de bureau Electron
6. Application mobile companion (Novaskol Connecte)
7. Application de bureau Connecte Desktop
8. Processus de packaging et déploiement
9. Interfaces principales
10. Conclusion

**Conclusion générale**

**Bibliographie**

**Annexes**
- Annexe A : Généralités sur les réseaux informatiques
- Annexe B : UML — Unified Modeling Language
- Annexe C : Guide d'installation de Novaskol
- Annexe D : Structure du paquet de distribution
- Annexe E : Tableau des routes API

---

## Liste des figures

Figure I.1 : Vues de l'architecture logicielle selon P. Kruchten
Figure I.2 : Principe de fonctionnement du Client/Serveur
Figure I.3 : Architecture à 2 niveaux
Figure I.4 : Architecture à 3 niveaux
Figure I.5 : Architecture multi-niveaux
Figure I.6 : Architecture MVC (Modèle-Vue-Contrôleur)
Figure II.1 : Architecture globale de Novaskol
Figure II.2 : Architecture Electron + Laravel
Figure II.3 : Diagramme de contexte de l'application
Figure II.4 : Schéma relationnel de la base de données
Figure III.1 : Démarche de modélisation de l'application
Figure III.2 : Diagramme de contexte
Figure III.3 : Diagramme de cas d'utilisation global
Figure III.4 : Diagramme de cas d'utilisation – Administrateur
Figure III.5 : Diagramme de cas d'utilisation – Enseignant
Figure III.6 : Diagramme de cas d'utilisation – Parent
Figure III.7 : Diagramme de cas d'utilisation – Personnel
Figure III.8 : Diagramme de séquence – Authentification
Figure III.9 : Diagramme de séquence – Saisie des notes
Figure III.10 : Diagramme de séquence – Génération de bulletin
Figure III.11 : Diagramme de séquence – Synchronisation
Figure III.12 : Diagramme d'activité – Authentification
Figure III.13 : Diagramme d'activité – Saisie des notes
Figure III.14 : Diagramme de classe global
Figure IV.1 : Interface de développement (VS Code)
Figure IV.2 : Structure du projet Laravel
Figure IV.3 : Architecture des contrôleurs
Figure IV.4 : Processus de démarrage de l'application Electron
Figure IV.5 : Architecture IPC (preload.cjs)
Figure IV.6 : Page d'accueil / Authentification
Figure IV.7 : Tableau de bord principal
Figure IV.8 : Interface de saisie des notes
Figure IV.9 : Interface de génération de bulletin
Figure IV.10 : Interface d'emploi du temps
Figure IV.11 : Interface de messagerie interne
Figure IV.12 : Interface de gestion des présences par QR code
Figure IV.13 : Application de bureau Electron (splash screen)
Figure IV.14 : Application Novaskol Connecte (mobile)
Figure IV.15 : Application Connecte Desktop
Figure IV.16 : Processus de packaging et déploiement
Figure IV.17 : Structure du paquet de distribution

---

## Liste des tableaux

Tableau I.1 : Comparaison des SGBD
Tableau I.2 : Comparaison des frameworks PHP
Tableau II.1 : Spécifications techniques de Novaskol
Tableau II.2 : Liste complète des tables de la base de données
Tableau II.3 : Dictionnaire des données (tables principales)
Tableau II.4 : Liste des 45 modules fonctionnels
Tableau III.1 : Identification des acteurs
Tableau III.2 : Spécification des cas d'utilisation
Tableau III.3 : Besoins fonctionnels détaillés
Tableau III.4 : Besoins non fonctionnels
Tableau III.5 : Correspondance acteurs-tâches
Tableau III.6 : Spécification des scénarios principaux
Tableau IV.1 : Outils de développement
Tableau IV.2 : Arborescence complète du projet
Tableau IV.3 : Liste des contrôleurs
Tableau IV.4 : Configuration electron-builder

---

## Introduction générale

L'informatique a profondément transformé notre société au cours des dernières décennies. La numérisation des processus administratifs et pédagogiques dans le domaine de l'éducation est devenue une nécessité incontournable pour améliorer l'efficacité, la transparence et la qualité du service rendu aux élèves, aux enseignants et aux parents.

Dans de nombreux établissements scolaires, en particulier dans les pays en développement et les zones rurales, la gestion des notes, des absences, des emplois du temps et de la communication avec les parents s'effectue encore de manière manuelle ou semi-automatisée à l'aide d'outils disparates. Cette situation engendre des problèmes majeurs : perte de temps, erreurs de saisie, difficultés de suivi, absence de centralisation des données, et communication limitée entre les différents acteurs de la communauté éducative.

Par ailleurs, les solutions de gestion scolaire existantes sur le marché présentent plusieurs limitations : elles sont souvent payantes, nécessitent une connexion Internet permanente, sont complexes à déployer, ou ne répondent pas aux spécificités du système éducatif local. La plupart des établissements scolaires ne disposent pas d'une infrastructure réseau fiable ni d'un accès Internet permanent, ce qui rend les solutions web traditionnelles inadaptées à leur contexte.

C'est dans ce contexte que s'inscrit notre projet de fin d'études : concevoir et réaliser une application de gestion scolaire complète, fonctionnant hors-ligne, déployable localement sur un simple PC sans nécessité de serveur dédié ni de connexion Internet. L'application, baptisée **Novaskol**, se présente sous la forme d'une application de bureau autonome (Electron + Laravel) couplée à une application mobile companion pour les usages mobiles, avec un mécanisme de synchronisation multi-appareils sur le réseau local.

**Note personnelle.** Mon parcours en Communication en Audiovisuelle et Numérique (CAN) m'a sensibilisé dès le début à la place du numérique dans notre société. Après la L3, j'ai naturellement orienté ma formation vers le développement web, fasciné par la capacité de créer des solutions accessibles, fonctionnelles et utiles. Bien que je ne vienne pas d'un cursus purement informatique, c'est précisément cette transition — du numérique vers la technique web — qui m'a poussé à choisir Novaskol comme projet. Je voyais dans ce système une opportunité de laisser une trace concrète, une solution que j'ai choisie, façonnée et portée de bout en bout, reflet de mon intérêt pour le web et de ma volonté de créer quelque chose qui a du sens.

Notre problématique centrale peut être formulée ainsi : **Comment concevoir et développer un système de gestion scolaire complet, accessible hors-ligne, facile à déployer et à utiliser, qui réponde aux besoins des établissements scolaires en matière de gestion administrative, pédagogique et financière ?**

Pour répondre à cette problématique, nous avons adopté une démarche méthodologique rigoureuse :

1. **Analyse des besoins** auprès des acteurs du système éducatif (direction, enseignants, parents, personnel administratif)
2. **Étude comparative** des solutions existantes et des technologies disponibles
3. **Conception architecturale** et modélisation UML complète
4. **Développement itératif** avec des cycles de test fréquents (méthodologie agile)
5. **Packaging et déploiement** pour une distribution facilitée via un installateur Windows

Les principales fonctionnalités offertes par Novaskol couvrent l'ensemble du cycle de gestion scolaire : inscription des élèves, gestion des classes et des matières, saisie et consultation des notes, génération de bulletins et de relevés, gestion des emplois du temps, suivi des présences par QR code, communication interne par messagerie instantanée, gestion financière (paiements, dépenses, salaires), gestion des ressources humaines, et synchronisation multi-appareils sur le réseau local.

Le système est articulé autour de 45 modules fonctionnels répartis en 8 catégories, 67 tables de base de données, et 33 migrations Laravel, le tout embarqué dans une application de bureau Electron de 650 Mo.

Ce mémoire est organisé en quatre chapitres :

Le **premier chapitre** présente l'état de l'art des technologies utilisées : architectures logicielles, modèle client/serveur, technologies web, frameworks PHP, systèmes de gestion de base de données, et applications de bureau hybrides.

Le **deuxième chapitre** est consacré à la présentation détaillée de Novaskol : son architecture globale, les technologies employées, sa base de données exhaustive, et l'ensemble de ses modules fonctionnels.

Le **troisième chapitre** détaille l'analyse et la conception du système à l'aide du langage UML : identification des acteurs, diagrammes de cas d'utilisation, de séquence, d'activité, et modèle conceptuel de données complet.

Le **quatrième chapitre** présente la réalisation pratique : environnement de développement, implémentation du backend et du frontend, application de bureau Electron avec son processus de démarrage, applications companion (mobile et desktop), processus de packaging et déploiement, et présentation des interfaces principales.

Enfin, une conclusion générale synthétise les résultats obtenus, les difficultés rencontrées, les compétences acquises, et propose des perspectives d'évolution pour le projet.

---

# Chapitre I : État de l'art et technologies utilisées

## 1. Introduction

La réalisation d'un système d'information moderne pour la gestion scolaire nécessite une maîtrise approfondie des concepts architecturaux et des technologies disponibles. Ce chapitre présente les fondements théoriques sur lesquels repose notre projet Novaskol : les architectures logicielles, le modèle client/serveur, les technologies web, les systèmes de gestion de base de données, et les frameworks modernes de développement d'applications de bureau et mobiles.

L'objectif de ce chapitre est de situer notre projet dans le paysage technologique actuel, de justifier nos choix techniques, et de fournir les bases conceptuelles nécessaires à la compréhension des chapitres suivants.

## 2. Architecture logicielle

### 2.1 Définition

L'architecture logicielle est la structure fondamentale d'un système logiciel, comprenant ses composants, leurs relations mutuelles et avec l'environnement, ainsi que les principes guidant sa conception et son évolution. Une bonne architecture permet de répondre aux exigences fonctionnelles et non fonctionnelles du système tout en facilitant sa maintenance et son évolution.

### 2.2 Le modèle 4+1 vues de Philippe Kruchten

Selon Philippe Kruchten (1995), l'architecture d'un système logiciel peut être représentée selon cinq vues complémentaires, chacune décrivant un aspect particulier du système :

**Vue Logique :** La vue logique se concentre sur la modélisation des principaux éléments d'architecture et mécanismes logiciels. Elle comprend les modèles d'analyse et de conception du système, définissant les entités métier, leurs relations et leur comportement. Dans le cadre de Novaskol, cette vue correspond aux modèles métier : élèves, enseignants, classes, matières, notes, bulletins, etc.

**Vue Composants :** La vue composants identifie les modules logiciels qui implémentent les éléments définis dans la vue logique. Pour Novaskol, ces composants incluent les contrôleurs (30+), les services métier (9), les middlewares (3), les modèles Eloquent, et les modules fonctionnels de l'application.

**Vue Processus :** Cette vue définit les processus, la coordination et la synchronisation des tâches. Dans notre architecture, cela correspond au serveur PHP intégré, aux files d'attente (queues Laravel) pour les tâches asynchrones, à la synchronisation multi-appareils, et aux processus Electron (main process, renderer process).

**Vue Déploiement :** La vue déploiement précise l'architecture de production : ressources matérielles, implantation des composants, et configuration réseau. Novaskol peut être déployé selon trois modes : local (application de bureau autonome avec SQLite), hébergement (serveur web Apache/Nginx avec MySQL), ou hybride (desktop + appareils connectés).

**Vue Use-Cases :** Les cas d'utilisation constituent le fil conducteur qui relie les quatre autres vues. Ils expriment les besoins fonctionnels du système du point de vue des utilisateurs finaux.

### 2.3 Le patron d'architecture MVC (Modèle-Vue-Contrôleur)

Le patron d'architecture MVC (Modèle-Vue-Contrôleur) est l'un des patrons d'architecture les plus utilisés dans le développement d'applications web. Il propose une séparation claire des responsabilités en trois composants distincts :

**Le Modèle (Model) :** Gère les données et la logique métier. Il encapsule l'accès à la base de données, les règles de validation, et les relations entre les entités. Dans Laravel, les modèles Eloquent ORM implémentent ce rôle, permettant d'interagir avec la base de données via une interface orientée objet.

**La Vue (View) :** Assure la présentation des données à l'utilisateur. Elle reçoit les données du contrôleur et les formate pour l'affichage. Novaskol utilise le moteur de templates Blade pour générer les interfaces HTML, avec TailwindCSS pour le style.

**Le Contrôleur (Controller) :** Fait le lien entre le modèle et la vue. Il traite les requêtes HTTP, orchestre la logique d'interaction, et prépare les données pour la vue.

Le schéma suivant illustre le fonctionnement de l'architecture MVC :

```
+------------------+     +------------------+     +------------------+
| Client           |---->| Controleur       |---->| Modele           |
| (Navigateur)     |     | (Controller)     |     | (Model)          |
|                  |<----|                  |<----|                  |
|                  |     | Vue              |     | Base de          |
|                  |     | (Blade)          |     | donnees          |
+------------------+     +------------------+     +------------------+
```

**Figure I.1 : Schéma de l'architecture MVC (Modèle-Vue-Contrôleur)**

## 3. Architectures distribuées

### 3.1 Définition

Une architecture distribuée est une architecture dont les ressources ne se trouvent pas au même endroit ou sur la même machine. Les fonctions de l'application sont réparties entre plusieurs systèmes interconnectés par un réseau.

### 3.2 Types d'architectures distribuées

**Architecture centralisée :** Toutes les ressources sont sur une seule machine. Le traitement, les données et la logique métier sont concentrés sur un seul système. Cette approche est simple à mettre en œuvre mais présente des limites en termes de passage à l'échelle et de tolérance aux pannes.

**Architecture client/serveur :** Répartition entre des clients (qui demandent des services) et un ou plusieurs serveurs (qui fournissent ces services). C'est le modèle le plus répandu pour les applications web.

**Architecture peer-to-peer (P2P) :** Chaque nœud est à la fois client et serveur. Les ressources sont partagées directement entre les pairs sans nécessité de serveur central.

**Architecture multi-tiers (n-tiers) :** L'application est divisée en plusieurs couches distinctes, chacune ayant une responsabilité spécifique. Cette séparation permet une meilleure maintenabilité, évolutivité et sécurité.

## 4. Modèle Client/Serveur

### 4.1 Définition

Le modèle client/serveur est un modèle de communication où des machines clientes contactent un serveur pour obtenir des services. Un client envoie une requête au serveur, qui la traite et retourne une réponse. Ce modèle est à la base de la plupart des applications réseau modernes, y compris les applications web.

### 4.2 Principe de fonctionnement

Le fonctionnement du modèle client/serveur suit un cycle requête-réponse en plusieurs étapes :

1. Le client établit une connexion avec le serveur
2. Le client envoie une requête décrivant l'opération souhaitée
3. Le serveur traite la requête (accès à la base de données, calculs, etc.)
4. Le serveur renvoie la réponse au client
5. Le client traite la réponse et l'affiche à l'utilisateur

Ce principe est illustré par la figure suivante :

```
+------------------+    Requete    +------------------+
| Client           |-------------->| Serveur          |
| (demande service)|               | (fournit service)|
|                  |<--------------|                  |
+------------------+    Reponse    +------------------+
```

**Figure I.2 : Principe de fonctionnement du Client/Serveur**

### 4.3 Architecture à 2 niveaux (2-tiers)

Dans cette configuration, le client interagit directement avec le serveur de base de données. Le client contient à la fois la logique de présentation et la logique métier, tandis que le serveur gère les données :

```
+------------------+    +------------------+
| Client           |<-->| Serveur BDD      |
| (IHM + Metier)   |    | (SQL)            |
+------------------+    +------------------+
```

**Figure I.3 : Architecture à 2 niveaux**

Cette architecture est simple à mettre en œuvre mais pose des problèmes de maintenance (mise à jour de la logique métier sur chaque poste client) et d'évolutivité.

### 4.4 Architecture à 3 niveaux (3-tiers)

L'architecture à trois niveaux introduit un niveau intermédiaire, le serveur d'application (middleware), qui contient la logique métier :

```
+------------------+    +------------------+    +------------------+
| Niveau 1         |    | Niveau 2         |    | Niveau 3         |
| Client           |<-->| Application      |<-->| Donnees          |
| (Interface)      |    | (Logique metier) |    | (Base de donnees)|
+------------------+    +------------------+    +------------------+
```

**Figure I.4 : Architecture à 3 niveaux**

Les avantages de cette architecture sont :
- Séparation claire des responsabilités
- Meilleure évolutivité (chaque niveau peut être mis à l'échelle indépendamment)
- Maintenance facilitée
- Sécurité renforcée

**Application dans Novaskol :**
- **Niveau 1 (Client)** : Navigateur web ou fenêtre Electron
- **Niveau 2 (Application)** : Serveur PHP/Laravel avec sa logique métier
- **Niveau 3 (Données)** : Base de données SQLite ou MySQL

### 4.5 Architecture multi-niveaux (n-tiers)

L'architecture multi-niveaux étend le modèle à 3 niveaux en décomposant davantage les couches. Novaskol, dans sa configuration Electron, utilise une variante de cette architecture avec les couches suivantes :

- **Couche présentation** : Interface utilisateur (HTML/Blade)
- **Couche contrôle** : Contrôleurs Laravel
- **Couche service** : Services métier
- **Couche persistance** : Modèles Eloquent
- **Couche données** : SQLite/MySQL

### 4.6 Middleware

Un middleware est un logiciel qui sert d'intermédiaire entre les différentes couches d'une architecture distribuée. Il facilite la communication, la gestion des transactions, et la coordination entre les composants.

Dans Laravel, les middlewares sont des filtres HTTP qui s'exécutent avant ou après le traitement d'une requête. Novaskol utilise un middleware personnalisé `module.access` pour le contrôle d'accès basé sur les modules.

## 5. Internet et le Web

### 5.1 Internet

Internet est un réseau mondial de réseaux interconnectés utilisant le protocole TCP/IP. Il permet l'échange de données entre des millions d'ordinateurs à travers le monde.

**Principaux protocoles :**
- **TCP/IP** : Protocole de base garantissant la transmission fiable des données
- **HTTP/HTTPS** : Protocole de transfert hypertexte
- **FTP** : Protocole de transfert de fichiers
- **SMTP/POP3/IMAP** : Protocoles de messagerie électronique
- **DNS** : Système de résolution de noms de domaine

### 5.2 Intranet et Extranet

- **Intranet** : Réseau interne à une organisation utilisant les technologies Internet, accessible uniquement aux membres de l'organisation
- **Extranet** : Extension de l'intranet permettant à des partenaires externes d'accéder à certaines ressources

### 5.3 Le World Wide Web

Le Web est un système hypermédia fonctionnant sur Internet, permettant de consulter des pages web liées entre elles par des hyperliens.

#### 5.3.1 Sites web statiques

Composés de pages HTML pré-générées, servies telles quelles par le serveur web. Chaque page est un fichier indépendant.

#### 5.3.2 Sites web dynamiques

Les pages sont générées à la volée en fonction des paramètres de la requête et des données stockées en base. Ils utilisent des langages côté serveur (PHP, Python, Java, etc.).

### 5.4 Le protocole HTTP

HTTP (HyperText Transfer Protocol) est le protocole de communication utilisé par le Web. Fonctionnant en mode requête/réponse, il permet aux navigateurs web de demander des ressources aux serveurs web.

Novaskol utilise le serveur HTTP intégré de PHP (Artisan serve) pour le mode local, et peut être déployé sous Apache ou Nginx pour le mode hébergé.

### 5.5 REST API

REST (Representational State Transfer) est un style d'architecture pour les systèmes hypermédia distribués. Novaskol expose des API RESTful pour la synchronisation entre l'application desktop et les applications mobiles Connecte.

## 6. Technologies de développement web

### 6.1 PHP

#### 6.1.1 Présentation

PHP (Hypertext Preprocessor) est un langage de programmation côté serveur, spécialement conçu pour le développement d'applications web. Créé par Rasmus Lerdorf en 1994, PHP est aujourd'hui l'un des langages les plus utilisés pour le développement web.

#### 6.1.2 PHP 8.2

La version 8.2 de PHP, utilisée dans Novaskol, apporte des améliorations significatives :

- **Type system amélioré** : Types d'union, type mixed, types statiques
- **JIT (Just-In-Time Compilation)** : Compilation à la volée pour des performances accrues
- **Named arguments** : Arguments nommés pour les fonctions
- **Match expression** : Alternative améliorée au switch
- **Readonly classes** : Classes immutables
- **Performance** : Jusqu'à 3x plus rapide que PHP 7.x

#### 6.1.3 Avantages de PHP pour Novaskol

- Large communauté et écosystème riche
- Support natif de SQLite (extension PDO_SQLITE)
- Facilité d'apprentissage et de déploiement
- Compatibilité avec tous les hébergeurs
- Performances accrues avec PHP 8.x
- Bibliothèque standard riche

### 6.2 Laravel

#### 6.2.1 Présentation

Laravel est un framework PHP open-source, créé par Taylor Otwell en 2011, qui suit le patron d'architecture MVC. Il est aujourd'hui le framework PHP le plus populaire.

#### 6.2.2 Version utilisée : Laravel 12

Laravel 12, utilisé dans Novaskol, offre les fonctionnalités suivantes :

**Eloquent ORM :** Mappage objet-relationnel intuitif. Les tables sont représentées par des classes PHP, et les relations sont définies sous forme de méthodes.

**Blade Template Engine :** Moteur de templates performant avec héritage de vues, sections, composants.

**Migration System :** Versionnement du schéma de base de données. Chaque modification de la structure est une classe PHP exécutable, permettant de déployer et d'annuler les changements.

**Artisan CLI :** Outil en ligne de commande pour les tâches courantes.

**Queues :** Gestion de tâches asynchrones.

**Security :** Protection intégrée contre les vulnérabilités web courantes (CSRF, XSS, SQL injection).

**Testing :** PHPUnit intégré avec des helpers de test.

#### 6.2.3 Fonctionnalités clés utilisées dans Novaskol

- Service Providers
- Middleware (module.access)
- Event & Listeners
- Filesystem
- Cache
- Validation
- Mail (notifications)
- Routing

### 6.3 Comparaison des frameworks PHP

| Critere | Laravel | Symfony | CodeIgniter | CakePHP |
|---------|---------|---------|------------|---------|
| Courbe d'apprentissage | Facile | Moderee | Facile | Moderee |
| Performance | Bonne | Bonne | Tres bonne | Bonne |
| ORM | Eloquent | Doctrine | Custom | CakePHP ORM |
| Templating | Blade | Twig | Custom | Custom |
| Communaute | Tres large | Large | Modelee | Modelee |
| Version actuelle | 12.x | 7.x | 4.x | 5.x |

**Tableau I.2 : Comparaison des frameworks PHP**

### 6.4 Node.js et npm

Node.js est un environnement d'exécution JavaScript côté serveur, construit sur le moteur V8 de Google Chrome. npm est le gestionnaire de paquets associé.

Dans Novaskol, ils sont utilisés pour :
- Le processus principal Electron
- Les outils de build (Vite, TailwindCSS, electron-builder)
- La gestion des dépendances frontend
- Les scripts de développement et de build

### 6.5 Vite

Vite est un outil de build moderne qui offre un développement rapide avec rechargement à chaud (HMR). Utilise les modules ES natifs en développement et Rollup pour la production.

Novaskol utilise Vite 7 pour compiler les assets CSS (TailwindCSS) et JavaScript.

### 6.6 TailwindCSS

TailwindCSS est un framework CSS utilitaire avec une approche "utility-first". Version 4 utilisée dans Novaskol, avec configuration automatique.

## 7. Systèmes de Gestion de Base de Données (SGBD)

### 7.1 Definition

Un Système de Gestion de Base de Données (SGBD) est un logiciel permettant de stocker, manipuler et interroger des données de manière structurée. Il assure la persistance, l'intégrité, la sécurité et la gestion des accès concurrents.

### 7.2 SQLite

SQLite est un SGBD relationnel embarqué, leger et sans serveur, stockant les donnees dans un fichier unique.

**Caractéristiques :**
- Aucune configuration serveur nécessaire
- Base de données = fichier unique
- Performant pour applications mono-utilisateur
- Supporté nativement par PHP
- Taille maximale : 281 To

**Utilisation dans Novaskol :**
Base de données par défaut pour le mode hors-ligne. Chaque installation Desktop crée son propre fichier SQLite.

### 7.3 MySQL/MariaDB

MySQL est un SGBD relationnel client/serveur. MariaDB est un fork communautaire.

**Caractéristiques :**
- Architecture client/serveur avec processus dédié
- Gestion multi-utilisateurs avancée
- Performances élevées
- Transactions ACID
- Procédures stockées, triggers, vues

**Utilisation dans Novaskol :**
Déploiements hébergés et installations multi-utilisateurs. MySQL 8.0 portable embarqué dans le runtime.

## 8. Applications de bureau hybrides

### 8.1 Electron

Electron est un framework open-source (GitHub, 2013) permettant de créer des applications de bureau multiplateformes avec HTML/CSS/JavaScript. Utilise Chromium pour le rendu et Node.js pour les API système.

**Version utilisée :** Electron 37.2.1

**Architecture :**
- **Main process (Node.js)** : Cycle de vie, fenêtres, API système, IPC
- **Renderer process (Chromium)** : Interface web, accès limité système

```
+--------------------------------------------------------+
|              APPLICATION ELECTRON                        |
|  +------------------+    +--------------------------+  |
|  | Main Process     |<-->| Renderer Process          |  |
|  | (Node.js)        | IPC| (Chromium)               |  |
|  |                  |    |                          |  |
|  | - Fenetres       |    | - HTML/CSS/JS            |  |
|  | - Systeme        |    | - Interface web          |  |
|  | - Fichiers       |    |                          |  |
|  +------------------+    +--------------------------+  |
+--------------------------------------------------------+
```

**Figure I.5 : Architecture d'une application Electron**

**Avantages pour Novaskol :**
- Packaging complet PHP + Laravel
- Déploiement simplifié (installateur unique)
- Fonctionnement hors-ligne
- Accès API système
- Expérience native

### 8.2 electron-builder

Outil de packaging pour applications Electron. Version 26.0.12 utilisée avec cible NSIS Windows.

**Configuration :**
- Installateur non one-click avec choix du répertoire
- Raccourcis Bureau et Menu Démarrer
- Ressources Laravel intégrées

### 8.3 Capacitor

Capacitor est un framework de développement d'applications mobiles hybrides, successeur de Cordova, développé par l'équipe Ionic. Il permet de transformer une application web en application mobile native.

**Version utilisée :** Capacitor 8.3.3

**Avantages :**
- Accès aux API natives (appareil photo, notifications, etc.)
- Génération de builds Android et iOS à partir d'une base de code web
- Performance proche du natif

## 9. Conclusion

Ce chapitre nous a permis de poser les bases théoriques et technologiques de notre projet. Le modèle client/serveur à 3 niveaux, couplé à l'architecture MVC de Laravel, constitue le socle architectural de Novaskol. Le choix des technologies (PHP 8.2, Laravel 12, SQLite, Electron 37, Capacitor 8) a été guidé par les impératifs de simplicité de déploiement, de fonctionnement hors-ligne, de maintenabilité et d'évolutivité. Le chapitre suivant présente l'architecture globale de Novaskol et comment ces technologies sont orchestrées.

---

# Chapitre II : Architecture et technologies de Novaskol

## 1. Introduction

Ce chapitre est consacré à la présentation détaillée de Novaskol : son contexte, son architecture globale, ses composants technologiques, sa base de données exhaustive, et l'ensemble des modules fonctionnels qui le composent. Nous décrivons également les spécificités architecturales qui font de Novaskol une solution innovante dans le paysage des systèmes de gestion scolaire, notamment son architecture Electron + Laravel embarqué et son système de synchronisation multi-appareils.

## 2. Présentation du projet Novaskol

### 2.1 Contexte et objectifs

Novaskol est un système de gestion scolaire complet conçu pour répondre aux besoins des établissements d'enseignement primaire, secondaire et technique. Le projet est né du constat que de nombreuses écoles, en particulier dans les zones à connectivité Internet limitée, ne disposent pas d'outils numériques adaptés à leur contexte.

L'auteur du projet est **Tojo Nambinina RANDRIAMIFALY**, et le projet est publié sous licence MIT.

**Objectifs principaux :**
1. Fournir un outil de gestion scolaire complet et accessible hors-ligne
2. Simplifier le déploiement via une application de bureau autonome
3. Permettre une synchronisation multi-appareils sur le réseau local
4. Offrir une interface adaptée à chaque profil d'utilisateur
5. Garantir la sécurité et l'intégrité des données
6. Assurer la portabilité sur Windows 10/11

### 2.2 Spécifications techniques

| Specification | Valeur |
|---|---|
| Version | 1.0.6 |
| Backend | Laravel 12.x (PHP 8.2+) |
| Application de bureau | Electron 37.2.1 |
| Base de donnees | SQLite (defaut) / MySQL 8.0 / MariaDB |
| Application mobile | React 19 + Capacitor 8 (Android + PWA) |
| Companion Desktop | Electron 37 (Windows) |
| Licence | MIT |
| Auteur | Tojo Nambinina RANDRIAMIFALY |

**Tableau II.1 : Spécifications techniques de Novaskol**

## 3. Architecture globale du système

### 3.1 Vue d'ensemble

L'architecture de Novaskol est organisée en plusieurs couches interconnectées, formant un écosystème complet de gestion scolaire :

```
+----------------------------------------------------------------------------------+
|                         APPLICATION DE BUREAU (Electron)                          |
|  +---------------------------------------------------------------------------+   |
|  |                         PROCESSUS PRINCIPAL (main.cjs)                    |   |
|  |  - Splash screen (460x360, frameless)                                     |   |
|  |  - Gestion du cycle de vie du serveur PHP                                 |   |
|  |  - Communication IPC via contextBridge (preload.cjs)                      |   |
|  |  - Fenetre principale (1440x920, min 1220x760)                            |   |
|  |  - Instance unique via app.requestSingleInstanceLock()                    |   |
|  +---------------------------------------------------------------------------+   |
|                                   |                                                |
|                                   v                                                |
|  +---------------------------------------------------------------------------+   |
|  |                         LARAVEL BACKEND (PHP 8.2.0)                       |   |
|  |  +----------------+  +----------------+  +-----------------------------+  |   |
|  |  | Controleurs    |  | Services       |  | Middleware & Auth           |  |   |
|  |  | (30 fichiers)  |  | (9 fichiers)   |  | (module.access)            |  |   |
|  |  +----------------+  +----------------+  +-----------------------------+  |   |
|  |  +----------------+  +----------------+  +-----------------------------+  |   |
|  |  | Routes (web)   |  | Models (ORM)   |  | Artisan Commands            |  |   |
|  |  | (287 lignes)   |  | (Eloquent)     |  | (novaskol:prepare, etc.)   |  |   |
|  |  +----------------+  +----------------+  +-----------------------------+  |   |
|  |  +----------------+  +----------------+  +-----------------------------+  |   |
|  |  | Vue Blade      |  | Assets CSS/JS  |  | Config (novaskol, app, etc)|  |   |
|  |  | (66 fichiers)  |  | (Vite+Tailwind)|  |                             |  |   |
|  |  +----------------+  +----------------+  +-----------------------------+  |   |
|  +---------------------------------------------------------------------------+   |
|                                   |                                                |
|                                   v                                                |
|  +---------------------------------------------------------------------------+   |
|  |                      BASE DE DONNEES (SQLite / MySQL)                     |   |
|  |                     ~42 tables, 33 migrations Laravel                     |   |
|  |         Dictionnaire de donnees complet (eleves, notes, bulletins...)     |   |
|  +---------------------------------------------------------------------------+   |
+----------------------------------------------------------------------------------+
          |                                                ^
          |                 Reseau Local                   |
          v                                                |
+--------------------------+    +-------------------------------+
| NOVASKOL CONNECTE        |    | NOVASKOL CONNECTE DESKTOP    |
| (Mobile - React 19       |    | (Application de bureau       |
|  Capacitor 8 - Android)  |<-->|  companion Electron)         |
| Version 0.2.0            |    | Version 1.0.0                |
| Pairing + Sync + Offline |    | Pairing + Sync + PHP emb.    |
+--------------------------+    +-------------------------------+
```

**Figure II.1 : Architecture globale de Novaskol**

### 3.2 L'application de bureau Electron (Novaskol Desktop)

L'application de bureau Novaskol est le composant central du système. Elle est construite avec Electron 37 et embarque l'ensemble des composants nécessaires à son fonctionnement autonome.

#### 3.2.1 Composants embarqués

- **Serveur PHP portable** (`tools/runtime/php/php.exe`) : PHP 8.2.0 avec les extensions suivantes activées : bz2, curl, gd, gettext, gmp, intl, imap, ldap, mbstring, exif, mysqli, openssl, pdo_mysql, pdo_sqlite, soap, sockets, sqlite3, xsl, zip
- **Serveur MySQL/MariaDB portable** (`tools/runtime/mysql/bin/mysqld.exe`) : MySQL 8.0.31 pour les installations nécessitant une base de données robuste
- **Application Laravel complète** avec toutes ses dépendances PHP (vendor)
- **Base de données SQLite** par défaut, sans configuration

#### 3.2.2 Processus de démarrage

Le processus de démarrage de l'application suit une séquence précise :

1. L'utilisateur lance `Novaskol.exe`
2. Une fenêtre de démarrage (splash screen) s'affiche (460x360, frameless, thème sombre #080e18)
3. Le processus principal Electron localise le binaire PHP (4 stratégies : ressources packagées, distribution source, développement, env vars)
4. Le fichier `.env` est configuré automatiquement avec les paramètres de production (APP_ENV=local, APP_DEBUG=false, DB_CONNECTION=sqlite, SESSION_DRIVER=file)
5. La clé d'application (APP_KEY) est générée si absente
6. Si le dossier vendor est absent, Composer est exécuté pour installer les dépendances
7. Les migrations de base de données sont exécutées (`php artisan migrate --force`)
8. Le serveur PHP intégré est démarré (`php -S 0.0.0.0:8001 router.php`)
9. L'application attend que le serveur HTTP réponde (ping jusqu'à 120 secondes)
10. La fenêtre principale charge l'URL `http://127.0.0.1:8001`

```
+----------+    +----------+    +----------+    +----------+    +----------+
| Lancement|--->| Splash   |--->| Init PHP |--->| Migrate  |--->| PHP Serve|
| .exe     |    | Screen   |    | + .env   |    | BDD      |    | :8001    |
+----------+    +----------+    +----------+    +----------+    +----------+
                                                                      |
+----------+    +----------+    +----------+    +----------+          |
| Ping OK  |<---| Wait     |<---| Health   |<---| PHP Art. |<---------+
|          |    | 120s max |    | Check    |    | serve    |
+----------+    +----------+    +----------+    +----------+
      |
      v
+----------+
| Fenetre  |
| Princip. |
+----------+
```

**Figure II.2 : Processus de démarrage de l'application Electron**

#### 3.2.3 Communication IPC (Inter-Process Communication)

La communication entre le processus principal (Node.js) et le processus de rendu (Chromium) s'effectue via un pont IPC sécurisé utilisant `contextBridge` :

```javascript
// preload.cjs - Pont IPC securise
contextBridge.exposeInMainWorld('desktopShell', {
    onSplashStatus: (cb) => ipcRenderer.on('splash-status', (_, m) => cb(m)),
    getMeta: () => ipcRenderer.invoke('desktop:get-meta'),
    print: () => ipcRenderer.send('print'),
    printHtml: (html) => ipcRenderer.send('print-html', html)
});
```

**Handlers IPC côté main :**
- `desktop:get-meta` : Retourne les informations de version
- `print` : Imprime la page courante
- `print-html` : Imprime un contenu HTML personnalisé

#### 3.2.4 Gestion du cycle de vie

- **Démarrage** : Splash screen → Initialisation → Fenêtre principale
- **Fermeture** : Boîte de confirmation "Voulez-vous vraiment quitter Novaskol ?" → Arrêt du serveur PHP → Nettoyage
- **Instance unique** : Lock d'instance pour éviter les lancements multiples

### 3.3 La synchronisation multi-appareils

Le système de synchronisation permet à plusieurs appareils de travailler sur les mêmes données via le réseau local. Il repose sur une architecture maître-esclave où l'appareil principal (Desktop Novaskol) sert de serveur central.

#### 3.3.1 Processus d'appairage

1. L'appareil secondaire (mobile ou desktop) scanne un QR code ou saisit un code d'appairage
2. Il ping le serveur principal via `/reseau-local/manifest-appareil`
3. Il envoie ses informations (UUID, type, plateforme, etc.)
4. Le serveur vérifie le code et autorise la connexion
5. Les données initiales sont transférées (bootstrap)

#### 3.3.2 Synchronisation bidirectionnelle

La synchronisation assure la cohérence des données entre tous les appareils connectés :

- **Push** : L'appareil connecté envoie ses modifications locales au serveur principal
- **Pull** : L'appareil connecté récupère les modifications des autres appareils
- **Intervalle** : Synchronisation automatique toutes les 5 minutes (Connecte Desktop)
- **File d'attente** : Les modifications sont mises en file d'attente en cas de déconnexion

#### 3.3.3 Gestion des conflits

En cas de modification concurrente sur le même enregistrement, un mécanisme de résolution de conflits est déclenché :

1. Les deux versions sont conservées (locale et entrante)
2. Un ticket de conflit est créé dans la table `sync_conflicts`
3. L'administrateur peut résoudre le conflit manuellement
4. La résolution choisie est propagée aux autres appareils

### 3.4 Modes de déploiement

| Mode | Description | Base de donnees | Usage |
|---|---|---|---|
| Local (Desktop) | Application autonome avec PHP embarque | SQLite | Ecole individuelle, hors-ligne |
| Hebergement | Serveur web Apache/Nginx classique | MySQL/MariaDB | Acces web permanent |
| Hybride | Desktop + Appareils connectes | SQLite + Sync reseau | Multi-poste local |

## 4. Technologies et outils utilisés

### 4.1 Backend

| Technologie | Role |
|---|---|
| PHP 8.2.0 | Langage de programmation serveur |
| Laravel 12 | Framework d'application web MVC |
| Composer 2.x | Gestionnaire de dependances PHP |
| barryvdh/laravel-dompdf ^2.3 | Generation de PDF (bulletins) |
| phpoffice/phpspreadsheet ^5.7 | Manipulation de fichiers Excel |
| chillerlan/php-qrcode ^5.0 | Generation de QR codes |
| laravel/pint | Correcteur de style de code |

### 4.2 Frontend

| Technologie | Role |
|---|---|
| Blade | Moteur de templates Laravel |
| TailwindCSS 4 | Framework CSS utilitaire |
| Vite 7 | Outil de build et bundler |
| JavaScript (vanilla + jQuery 3.6) | Interactivite client |
| Chart.js 4 | Graphiques statistiques |
| FullCalendar 6 | Calendrier interactif |
| Font Awesome 6 | Icones |
| SweetAlert2 11.x | Boites de dialogue et notifications |
| Axios | Requetes HTTP |

### 4.3 Desktop

| Technologie | Role |
|---|---|
| Electron 37.2.1 | Framework d'application de bureau |
| electron-builder 26.0.12 | Packaging et creation d'installateur NSIS |
| Node.js 22 | Environnement d'execution |

### 4.4 Mobile

| Technologie | Role |
|---|---|
| React 19 | Framework d'interface utilisateur |
| Capacitor 8.3.3 | Pont natif pour applications mobiles |
| Vite 8 | Outil de build |
| Service Worker | Cache hors-ligne PWA |

## 5. Architecture détaillée de la base de données

### 5.1 Présentation

La base de données de Novaskol comprend environ 42 tables organisées en plusieurs domaines fonctionnels. Le système de migration de Laravel (33 migrations) permet de versionner et de déployer le schéma automatiquement.

Le projet a été initialement développé avec MySQL (base legacy `bulletin_system`), puis migré vers Laravel avec support SQLite pour le mode hors-ligne. Les migrations couvrent la période de mai à juillet 2026.

### 5.2 Liste complète des tables

Tableau II.2 : Liste complète des tables de la base de données

| # | Table | Objectif | Migree depuis legacy |
|---|---|---|---|
| 1 | utilisateurs | Utilisateurs de l'application (admin, enseignant, staff, parent) | Oui |
| 2 | sessions | Sessions PHP/Laravel | Non (Laravel) |
| 3 | cache | Cache applicatif | Non (Laravel) |
| 4 | cache_locks | Verrouillage du cache | Non (Laravel) |
| 5 | jobs | Files d'attente de taches | Non (Laravel) |
| 6 | job_batches | Lots de travaux | Non (Laravel) |
| 7 | failed_jobs | Historique des echecs | Non (Laravel) |
| 8 | ecole | Informations sur l'etablissement | Oui |
| 9 | parametres | Parametres de configuration | Oui |
| 10 | permissions | Permissions d'acces aux modules | Oui |
| 11 | classes | Classes (nom, niveau) | Oui |
| 12 | eleves | Eleves inscrits | Oui |
| 13 | matieres | Matieres enseignees | Oui |
| 14 | classe_matieres | Association classe-matiere (coefficient) | Oui |
| 15 | professeurs | Enseignants | Oui |
| 16 | professeurs_classes | Affectation enseignant-classe-matiere | Oui |
| 17 | notes | Notes des eleves | Oui |
| 18 | bulletins | Bulletins de notes | Oui |
| 19 | remarques | Remarques/commentaires | Oui |
| 20 | examen_blanc | Examens blancs | Oui |
| 21 | remarques_examen_blanc | Remarques examens blancs | Oui |
| 22 | emploi_du_temps | Emploi du temps | Oui |
| 23 | evenements | Evenements du calendrier scolaire | Oui |
| 24 | presence_eleves | Presence des eleves | Oui |
| 25 | parents | Parents/tuteurs | Oui |
| 26 | parent_eleves | Lien parent-eleve | Oui |
| 27 | enseignants | Table enseignants alternative | Oui |
| 28 | staff | Personnel non enseignant | Oui |
| 29 | departements | Departements | Oui |
| 30 | presence_personnels | Presence du personnel | Oui |
| 31 | presence_staff | Presence du personnel (bis) | Oui |
| 32 | conversations | Conversations (privee/groupe) | Oui |
| 33 | conversation_participants | Participants aux conversations | Oui |
| 34 | messages | Messages | Oui |
| 35 | message_reactions | Reactions aux messages (emojis) | Oui |
| 36 | notifications | Notifications systeme | Oui |
| 37 | types_paiements | Types de paiement (frais scolaire) | Oui |
| 38 | paiements | Paiements effectues | Oui |
| 39 | paiements_assignes | Paiements assignes | Oui |
| 40 | depenses | Depenses | Oui |
| 41 | revenus | Revenus | Oui |
| 42 | salaires_assignes | Salaires du personnel | Oui |
| 43 | roles | Roles utilisateur | Oui |
| 44 | dossiers | Dossiers documentaires | Oui |
| 45 | fichiers | Fichiers dans les dossiers | Oui |
| 46 | typing_status | Indicateurs de saisie (chat) | Oui |
| 47 | personnes | Personnes generiques | Oui |
| 48 | equipements | Equipements / inventaire | Oui |
| 49 | ressources | Ressources | Oui |
| 50 | salles | Salles | Oui |
| 51 | reservations | Reservations de salles | Oui |
| 52 | reservations_ressources | Reservation-ressource pivot | Oui |
| 53 | licence | Licence logicielle | Oui |
| 54 | mpiasa | Employes (modele Malagasy) | Oui |
| 55 | teacher_lessons | Plans de cours des enseignants | Non |
| 56 | teacher_tasks | Taches des enseignants | Non |
| 57 | sync_devices | Appareils connectes | Non |
| 58 | sync_batches | Lots de synchronisation | Non |
| 59 | sync_changes | Modifications synchronisees | Non |
| 60 | sync_conflicts | Conflits de synchronisation | Non |
| 61 | sync_record_keys | Cles UUID synchronisees | Non |
| 62 | retards_personnels | Retards du personnel (legacy) | Oui |
| 63 | users | Utilisateurs Laravel (stock) | Non |
| 64 | password_reset_tokens | Jetons reset mot de passe | Non |
| 65 | chat_typing_status | Statut de saisie (legacy) | Oui |

### 5.3 Dictionnaire de données (tables principales)

**Tableau II.3 : Dictionnaire des données (tables principales)**

**Table eleves (eleves)**
| Champ | Type | Description |
|---|---|---|
| id | BIGINT PK | Identifiant unique |
| prenom | VARCHAR(100) | Prenom de l'eleve |
| nom | VARCHAR(100) | Nom de l'eleve |
| date_naissance | DATE | Date de naissance |
| lieu_naissance | VARCHAR(150) | Lieu de naissance |
| adresse | VARCHAR(255) | Adresse de l'eleve |
| numero_acte | VARCHAR(100) | Numero d'acte de naissance |
| fonkotany | VARCHAR(150) | Fokontany d'origine |
| commune | VARCHAR(150) | Commune d'origine |
| ecole_ancienne | VARCHAR(150) | Ecole precedente |
| nom_pere | VARCHAR(150) | Nom du pere |
| nom_mere | VARCHAR(150) | Nom de la mere |
| telephone | VARCHAR(50) | Telephone |
| telephone_pere | VARCHAR(50) | Telephone du pere |
| telephone_mere | VARCHAR(50) | Telephone de la mere |
| profession_pere | VARCHAR(150) | Profession du pere |
| profession_mere | VARCHAR(150) | Profession de la mere |
| adresse_pere | VARCHAR(255) | Adresse du pere |
| adresse_mere | VARCHAR(255) | Adresse de la mere |
| id_classe | BIGINT FK | Classe de l'eleve |
| matricule | VARCHAR(100) | Matricule unique |
| annee_scolaire | VARCHAR(20) | Annee scolaire |
| genre | ENUM('F','G') | Genre |
| statut | ENUM('nouveau','passant','redoublant') | Statut |
| photo | VARCHAR(255) | Photo d'identite |
| qr_token | VARCHAR(100) | Token QR pour carte |

**Table notes (notes)**
| Champ | Type | Description |
|---|---|---|
| id | BIGINT PK | Identifiant unique |
| eleve_id | BIGINT FK | Eleve concerne |
| matiere_id | BIGINT FK | Matiere |
| professeur_id | BIGINT FK | Professeur |
| valeur | FLOAT | Note (0-20) |
| trimestre | INTEGER | Trimestre (1-3) |
| annee_scolaire | VARCHAR(20) | Annee scolaire |
| coefficient | FLOAT | Coefficient |
| periode | VARCHAR(10) | Periode (B1, B2, T1, etc.) |
| observation | TEXT | Observation |

**Table bulletins (bulletins)**
| Champ | Type | Description |
|---|---|---|
| id | BIGINT PK | Identifiant unique |
| id_eleve | BIGINT FK | Eleve |
| trimestre | INTEGER | Trimestre |
| moyenne | FLOAT | Moyenne generale |
| mention | VARCHAR(100) | Mention obtenue |
| appreciation | TEXT | Appreciation |
| annee_scolaire | VARCHAR(20) | Annee scolaire |

**Table professeurs (professeurs)**
| Champ | Type | Description |
|---|---|---|
| id | BIGINT PK | Identifiant unique |
| nom | VARCHAR(100) | Nom |
| prenom | VARCHAR(100) | Prenom |
| email | VARCHAR(100) | Email (unique) |
| telephone | VARCHAR(50) | Telephone |
| specialisation | VARCHAR(100) | Specialisation |
| matiere_id | BIGINT FK | Matiere principale |
| salaire_horaire | DECIMAL(10,2) | Salaire horaire |
| diplome_pedagogique | VARCHAR(100) | Diplome pedagogique |
| autorisation_enseigner | VARCHAR(50) | Autorisation |
| annees_experience | INTEGER | Annees d'experience |
| statut | VARCHAR(50) | Statut (actif/inactif) |
| photo | VARCHAR(255) | Photo |

### 5.4 Relations principales

Le diagramme ci-dessous illustre les relations entre les entités principales :

- Un **Eleve** appartient a une **Classe** (N:1 via `id_classe`)
- Une **Classe** a plusieurs **Matieres** (N:N via `classe_matieres` avec coefficient)
- Un **Professeur** enseigne dans plusieurs **Classes** et **Matieres** (N:N via `professeurs_classes`)
- Un **Eleve** a plusieurs **Notes** par matiere (1:N)
- Un **Bulletin** est lie a un **Eleve** et un trimestre (N:1)
- Un **Parent** est lie a plusieurs **Eleves** (N:N via `parent_eleves`)
- Un **Message** appartient a une **Conversation** (N:1)
- Un **Paiement** est lie a un **Type de paiement** (N:1)
- Un **Professeur** a des **Lecons** planifiees (1:N via `teacher_lessons`)
- Un **Appareil** connecte a une session de synchronisation (1:N via `sync_batches`)

### 5.5 Système de synchronisation

Les tables de synchronisation forment un système robuste de réplication de données :

- `sync_devices` : Enregistre chaque appareil connecté avec son UUID unique, son type, sa plateforme, son adresse IP, et son statut d'autorisation
- `sync_batches` : Regroupe les modifications par lot avec un statut (en_attente, en_cours, termine, erreur)
- `sync_changes` : Stocke chaque modification avec la table concernée, l'UUID de l'enregistrement, l'opération (create, update, delete), et le payload JSON
- `sync_conflicts` : Enregistre les conflits avec les versions locale et entrante pour résolution
- `sync_record_keys` : Maintient une correspondance entre les IDs locaux et les UUIDs globaux

## 6. Modules et fonctionnalités

Novaskol propose 45 modules fonctionnels répartis en 8 catégories. Chaque module est accessible via un middleware de permission spécifique (`module.access:{cle_module}`).

### 6.1 Administration generale

| Module | Route | Description |
|---|---|---|
| Dashboard / Accueil | /dashboard | Tableau de bord avec statistiques, evenements, notifications |
| Ecole | /ecole | Gestion des informations de l'etablissement (nom, logo) |
| Parametres | /parametres | Configuration systeme, sauvegardes, diagnostic, guide |
| Comptes utilisateurs | /comptes-utilisateurs | Gestion des comptes et profils |

### 6.2 Gestion des inscriptions

| Module | Route | Description |
|---|---|---|
| Inscription | /inscription | Enregistrement des eleves, import Excel, recherche |
| Liste des classes | /classes | CRUD des classes, affectation des eleves |
| Matieres | /matieres | Gestion des matieres, coefficients par classe |
| Depot de dossier | /depot-dossier | Gestion des documents administratifs |

### 6.3 Gestion pedagogique

| Module | Route | Description |
|---|---|---|
| Notes | /notes | Saisie des notes par matiere et trimestre |
| Bulletins | /bulletin | Generation de bulletins (individuel, classe, annuel) |
| Resultats | /resultats | Consultation des resultats |
| Examens blancs | /examen-blanc | Gestion des examens blancs |
| Emploi du temps | /emploi-du-temps | Planification des cours par classe |
| Presence eleves | /presence-etudiant | Suivi des presences par QR code |
| Presence numerique | /presence-etudiant/numerique | Pointage numerique |
| Calendrier | /calendrier | Calendrier des evenements scolaires |
| Fiche de suivi | /fpe | Suivi pedagogique (FPE) |
| Carte scolaire | /cartes | Generation de cartes avec QR code |
| Assurance | /liste-assurance | Liste des eleves assures |
| Espace enseignant | /enseignant/espace | Plans de cours, progressions |

### 6.4 Communication

| Module | Route | Description |
|---|---|---|
| Messagerie generale | /communication | Chat general |
| Messagerie privee | /chat-prive | Chat prive 1-1 |
| Messagerie groupe | /chat-groupe | Chat de groupe |
| Notifications | /notifications | Notifications systeme |

### 6.5 Gestion des ressources humaines

| Module | Route | Description |
|---|---|---|
| Enseignants | /enseignants | Gestion des enseignants |
| Personnels | /staff | Gestion du personnel non enseignant |
| Presence personnels | /presence | Suivi des presences enseignants |
| Presence staff | /presence-staff | Suivi des presences personnel |
| Pointage unifie | /pointage | Pointage centralise par QR code |
| Permissions | /permissions | Controle d'acces aux modules |
| Gestion ressources | /gestion-ressource | Salles, equipements, ressources |

### 6.6 Gestion financiere

| Module | Route | Description |
|---|---|---|
| Types de paiement | /detail-paiement/type | Configuration frais de scolarite |
| Comptabilite | /comptable | Enregistrement des paiements |
| Liste des paiements | /liste-paiements | Consultation des transactions |
| Factures | /facture | Generation de factures et recus |
| Depenses | (dans comptable) | Gestion des depenses |
| Rapports comptables | /rapport-comptable | Etats financiers |
| Salaires | /detail-paiement/salaires | Gestion des salaires |

### 6.7 Portail parents

| Module | Route | Description |
|---|---|---|
| Espace parent | /parent/espace | Suivi resultats, presence, paiements |
| Communication parent | /parent/responsable-chat | Chat avec les enseignants |

### 6.8 Systeme

| Module | Route | Description |
|---|---|---|
| Diagnostics | /diagnostic-systeme | Verification etat du systeme |
| Sauvegardes | /sauvegardes | Creation, telechargement, restauration |
| Reseau local | /reseau-local | Appairage, synchronisation |
| Guide d'utilisation | /guide-utilisation | Aide integree |
| A propos | /apropos-novaskol | Informations version |

**Tableau II.4 : Liste des 45 modules fonctionnels**

## 7. Conclusion

Ce chapitre a présenté l'architecture globale et les composants de Novaskol. Le système combine un backend Laravel robuste, une application de bureau Electron autonome embarquant PHP et SQLite, et deux applications companion (mobile React/Capacitor et desktop Electron). Avec 45 modules fonctionnels couvrant 8 domaines et une base de données de 42 tables, Novaskol offre une couverture complète des besoins de gestion d'un établissement scolaire. Le mécanisme de synchronisation multi-appareils permet un travail collaboratif sur le réseau local, sans dépendance Internet.

Le chapitre suivant détaille la phase d'analyse et de conception du système à l'aide du langage UML.

---

# Chapitre III : Analyse et conception

## 1. Introduction

Ce chapitre est consacré à l'analyse et à la conception du système Novaskol à l'aide du langage de modélisation UML (Unified Modeling Language). Nous présentons successivement l'analyse des besoins, l'identification des acteurs, les diagrammes de cas d'utilisation, les diagrammes de séquence, les diagrammes d'activité, et le modèle conceptuel de données.

La démarche de modélisation adoptée suit les étapes classiques de l'ingénierie des systèmes d'information :

1. Recueil et analyse des besoins
2. Identification des acteurs et des cas d'utilisation
3. Modélisation dynamique (diagrammes de séquence et d'activité)
4. Modélisation statique (diagramme de classes)

```
+------------------+    +------------------+    +------------------+
| Analyse des      |--->| Modelisation     |--->| Modelisation     |
| besoins          |    | dynamique        |    | statique         |
| (Acteurs, CU)   |    | (Sequence,       |    | (Classe,         |
|                  |    |  Activite)       |    |  Base de donnees)|
+------------------+    +------------------+    +------------------+
```

**Figure III.1 : Démarche de modélisation de l'application**

## 2. Analyse des besoins

### 2.1 Besoins fonctionnels

Les besoins fonctionnels définissent les fonctionnalités que le système doit offrir. Pour Novaskol, nous avons identifié 16 besoins fonctionnels principaux :

| ID | Besoin | Description | Module concerne |
|---|---|---|---|
| BF01 | Authentification | Le systeme doit permettre aux utilisateurs de s'authentifier avec email, mot de passe et role | Auth |
| BF02 | Gestion des profils | Chaque utilisateur dispose d'un profil avec des permissions specifiques | Comptes |
| BF03 | Gestion des eleves | Inscription, modification, consultation et suppression des eleves | Inscription |
| BF04 | Gestion des classes | Creation, modification et organisation des classes | Classes |
| BF05 | Gestion des matieres | Definition des matieres et de leurs coefficients par classe | Matieres |
| BF06 | Saisie des notes | Les enseignants peuvent saisir les notes des eleves | Notes |
| BF07 | Generation de bulletins | Generation automatique des bulletins avec calcul des moyennes | Bulletin |
| BF08 | Emploi du temps | Planification et affichage des emplois du temps | Emploi du temps |
| BF09 | Suivi des presences | Suivi des presences par QR code ou manuellement | Presence |
| BF10 | Communication | Messagerie interne entre utilisateurs | Communication |
| BF11 | Gestion financiere | Suivi des paiements, depenses et revenus | Comptable |
| BF12 | Gestion RH | Gestion des enseignants et du personnel | RH |
| BF13 | Calendrier | Gestion des evenements scolaires | Calendrier |
| BF14 | Sauvegardes | Sauvegarde et restauration de la base de donnees | Sauvegardes |
| BF15 | Synchronisation | Synchronisation multi-appareils sur reseau local | Reseau local |
| BF16 | Rapports | Generation de rapports (presences, notes, finances) | Rapports |

**Tableau III.1 : Besoins fonctionnels détaillés**

### 2.2 Besoins non fonctionnels

Les besoins non fonctionnels définissent les contraintes et qualités du système :

| ID | Besoin | Description | Priorite |
|---|---|---|---|
| BNF01 | Hors-ligne | L'application doit fonctionner sans connexion Internet | Haute |
| BNF02 | Performance | Les temps de reponse doivent etre < 2 secondes | Haute |
| BNF03 | Securite | Mots de passe haches (bcrypt), acces controles par module | Haute |
| BNF04 | Portabilite | Fonctionnement sur Windows 10/11 64-bit | Haute |
| BNF05 | Evolutivite | Architecture permettant l'ajout de nouveaux modules | Moyenne |
| BNF06 | Maintenabilite | Code structure, utilise des standards (MVC, PSR-4) | Moyenne |
| BNF07 | Sauvegarde automatique | Sauvegarde facile de la base de donnees (fichier unique) | Haute |
| BNF08 | Interface intuitive | Interface accessible aux utilisateurs non techniques | Haute |
| BNF09 | Multilingue | Support de plusieurs langues (6 langues) | Basse |
| BNF10 | Securite des acces | Permissions modulables par utilisateur | Haute |

**Tableau III.2 : Besoins non fonctionnels**

## 3. Identification des acteurs

Un acteur est une entité (personne ou système) qui interagit avec le système. Pour Novaskol, nous avons identifié 7 acteurs principaux :

| Acteur | Description | Cas d'utilisation principaux |
|---|---|---|
| Administrateur | Gere l'ensemble du systeme, les utilisateurs, les permissions | Gestion complete du systeme |
| Enseignant | Saisit les notes, gere les presences, planifie les cours | Saisie notes, presence, cours |
| Eleve | Consulte ses notes, son emploi du temps, ses presences | Consultation |
| Parent | Consulte les resultats et la presence de ses enfants | Consultation, communication |
| Agent de scolarite | Gere les inscriptions, les dossiers, les paiements | Inscription, paiements |
| Chef d'etablissement | Supervise l'ensemble, consulte les rapports | Rapports, supervision |
| Personnel (staff) | Gere les ressources, la logistique | RH, ressources |

**Tableau III.3 : Identification des acteurs**

### 3.1 Correspondance acteurs-tâches

| Tache | Admin | Enseignant | Eleve | Parent | Scolarite | Chef | Staff |
|---|---|---|---|---|---|---|---|
| Authentification | X | X | X | X | X | X | X |
| Gestion eleves | X | | | | X | X | |
| Saisie notes | | X | | | | X | |
| Consultation notes | X | X | X | X | X | X | X |
| Bulletins | X | X | | X | X | X | |
| Emploi du temps | X | X | X | X | X | X | |
| Presence eleves | X | X | | X | | X | |
| Communication | X | X | X | X | X | X | X |
| Paiements | X | | | X | X | X | |
| Gestion RH | X | | | | | X | |
| Rapports | X | | | | X | X | |
| Parametres | X | | | | | | |
| Synchronisation | X | | | | | | |

**Tableau III.4 : Correspondance acteurs-tâches**

## 4. Diagrammes de cas d'utilisation

### 4.1 Diagramme de contexte

Le diagramme de contexte présente une vue d'ensemble du système et de ses interactions avec les acteurs externes :

```
+------------------+         +---------------------------+
| Administrateur   |<------->|                           |
+------------------+         |                           |
                             |       SYSTEME             |
+------------------+         |       NOVASKOL            |
| Enseignant       |<------->|                           |
+------------------+         |    - Authentification     |
                             |    - Gestion eleves       |
+------------------+         |    - Saisie notes         |
| Eleve            |<------->|    - Bulletins            |
+------------------+         |    - Emploi du temps      |
                             |    - Presence             |
+------------------+         |    - Communication        |
| Parent           |<------->|    - Paiements            |
+------------------+         |    - Synchronisation      |
                             |    - Rapports             |
+------------------+         |                           |
| Agent scolarite  |<------->|                           |
+------------------+         +---------------------------+

+------------------+
| Personnel (Staff)|
+------------------+
```

**Figure III.2 : Diagramme de contexte**

### 4.2 Diagramme de cas d'utilisation global

Le diagramme de cas d'utilisation global présente l'ensemble des fonctionnalités du système vues par les différents acteurs, avec leurs relations d'inclusion et d'extension.

**Cas d'utilisation pour l'Administrateur :**
- Gerer l'etablissement (informations, configuration)
- Gerer les utilisateurs (creation, modification, permissions)
- Gerer la base de donnees (sauvegardes, restauration)
- Configurer les parametres systeme
- Acceder au diagnostic systeme
- Gerer la synchronisation reseau
- Consulter les rapports
- Gerer les modules (activation/desactivation)

**Cas d'utilisation pour l'Enseignant :**
- S'authentifier
- Consulter son emploi du temps
- Saisir les notes des eleves
- Consulter les resultats
- Gerer les presences des eleves (QR code / manuel)
- Planifier les cours (lecons, taches)
- Communiquer via la messagerie
- Consulter le calendrier
- Generer des bulletins
- Gerer les examens blancs

**Cas d'utilisation pour le Parent :**
- S'authentifier (via un portail dedie)
- Consulter les notes de son enfant
- Consulter les presences de son enfant
- Consulter les bulletins
- Communiquer avec les enseignants
- Consulter les paiements

**Cas d'utilisation pour l'Agent de scolarite :**
- Gerer les inscriptions
- Gerer les dossiers documentaires
- Gerer les paiements
- Consulter les statistiques

**Cas d'utilisation pour le Personnel (Staff) :**
- Pointer sa presence (QR code)
- Gerer les ressources
- Gerer les salles et equipements

### 4.3 Diagramme de cas d'utilisation - Authentification

```
+--------------------------------------------+
|            SYSTEME NOVASKOL                 |
|  +--------------------------------------+  |
|  |  S'authentifier  <------  Acteur     |  |
|  |       |                              |  |
|  |       +--- <<include>> --> Verifier  |  |
|  |       |                  identifiants|  |
|  |       |                              |  |
|  |       +--- <<include>> --> Gerer     |  |
|  |                            session   |  |
|  +--------------------------------------+  |
|  +--------------------------------------+  |
|  |  Gerer mot de passe                  |  |
|  +--------------------------------------+  |
|  +--------------------------------------+  |
|  |  Choisir role (admin/enseignant/    |  |
|  |           staff/parent)             |  |
|  +--------------------------------------+  |
+--------------------------------------------+
```

**Figure III.3 : Diagramme de cas d'utilisation - Authentification**

### 4.4 Diagramme de cas d'utilisation - Saisie des notes

```
+--------------------------------------------+
|            SYSTEME NOVASKOL                 |
|  +--------------------------------------+  |
|  |  Saisir notes  <------ Enseignant   |  |
|  |       |                              |  |
|  |       +--- <<include>> --> Verifier  |  |
|  |       |                  permissions |  |
|  |       |                              |  |
|  |       +--- <<include>> --> Charger   |  |
|  |       |                  liste eleves|  |
|  |       |                              |  |
|  |       +--- <<include>> --> Valider   |  |
|  |                            notes     |  |
|  |       |                              |  |
|  |       +--- <<include>> --> Calculer  |  |
|  |                            moyennes  |  |
|  +--------------------------------------+  |
|  +--------------------------------------+  |
|  |  Selectionner classe + matiere       |  |
|  +--------------------------------------+  |
|  +--------------------------------------+  |
|  |  Selectionner trimestre              |  |
|  +--------------------------------------+  |
+--------------------------------------------+
```

**Figure III.4 : Diagramme de cas d'utilisation - Saisie des notes**

## 5. Diagrammes de séquence

Les diagrammes de séquence illustrent les interactions entre les objets du système au cours du temps. Ils montrent l'ordre des messages échangés.

### 5.1 Diagramme de séquence - Authentification

Le diagramme suivant illustre le processus d'authentification d'un utilisateur dans Novaskol :

```
Utilsateur      Controleur      Base de donnees     Session
    |               |                  |               |
    |-- email, pwd, role --->|          |               |
    |               |-- SELECT --------->|              |
    |               |<-- user data ------|              |
    |               |                  |               |
    |               |-- Verifier hash ---|              |
    |               |                  |               |
    |               | (si valide)      |               |
    |               |-- Creer session ---------------->|
    |               |                  |               |
    |               |-- Charger perms ->|              |
    |               |<-- permissions ---|              |
    |               |                  |               |
    |<-- Redirection dashboard --------|               |
    |               |                  |               |
    |               | (si invalide)    |               |
    |<-- Message d'erreur -------------|               |
    |               |                  |               |

```

**Figure III.5 : Diagramme de séquence - Authentification**

**Scénario nominal :**
1. L'utilisateur saisit ses identifiants (email, mot de passe, rôle)
2. Le contrôleur interroge la base de données pour trouver l'utilisateur
3. Le mot de passe est vérifié avec `Hash::check()`
4. Si valide, une session est créée avec les informations utilisateur
5. Les permissions sont chargées depuis la table `permissions`
6. L'utilisateur est redirigé vers le tableau de bord

**Scénario d'échec :**
1. Identifiants invalides → message d'erreur
2. Compte inactif → message d'accès refusé

### 5.2 Diagramme de séquence - Saisie des notes

```
Enseignant      Controleur      BDD (notes)    BDD (eleves)
    |               |               |               |
    |-- classe, matiere, trim --->|  |               |
    |               |-- SELECT eleves -------------->|
    |               |<-- liste eleves ---------------|
    |               |               |               |
    |<-- affichage formulaire ------|               |
    |               |               |               |
    |-- notes eleves -------------->|               |
    |               |-- Verifier permissions         |
    |               |-- Valider notes                |
    |               |-- INSERT notes -->|            |
    |               |<-- OK ------------|            |
    |               |               |               |
    |<-- Confirmation --------------|               |
    |               |               |               |

```

**Figure III.6 : Diagramme de séquence - Saisie des notes**

**Scénario nominal :**
1. L'enseignant sélectionne une classe, une matière et un trimestre
2. Le système charge la liste des élèves de cette classe
3. L'enseignant saisit les notes pour chaque élève
4. Les notes sont validées (bornes 0-20, format numérique)
5. Les notes sont enregistrées dans la base de données
6. Une confirmation est affichée

### 5.3 Diagramme de séquence - Génération de bulletin

```
Utilisateur     Controleur      Service        BDD
    |               |               |           |
    |-- classe, trimestre -------->|           |
    |               |-- Charger notes -------->|
    |               |<-- notes ---------------|
    |               |               |           |
    |               |-- Calculer moyennes      |
    |               |   par matiere            |
    |               |               |           |
    |               |-- Calculer moyenne       |
    |               |   generale               |
    |               |               |           |
    |               |-- Calculer rang          |
    |               |               |           |
    |               |-- Determiner mention     |
    |               |               |           |
    |               |-- Generer HTML bulletin  |
    |<-- Affichage bulletin ------|           |
    |               |               |           |
    |-- Export PDF --------------->|           |
    |               |-- DOMPDF::loadView()     |
    |<-- Telechargement PDF -------|           |
    |               |               |           |

```

**Figure III.7 : Diagramme de séquence - Génération de bulletin**

**Algorithme de calcul du bulletin :**
1. Récupération de toutes les notes de l'élève pour le trimestre
2. Calcul de la moyenne par matière (somme des notes × coefficient / somme des coefficients)
3. Calcul de la moyenne générale (somme des moyennes pondérées / somme des coefficients)
4. Calcul du rang (classement des élèves par moyenne générale)
5. Détermination de la mention en fonction de la moyenne
6. Génération du bulletin en HTML, puis export PDF via DOMPDF

### 5.4 Diagramme de séquence - Synchronisation

```
Appareil        Appareil        Serveur         BDD
Connecte        Principal       (API)           (Princ.)
    |               |               |               |
    |-- POST /appairer-appareil --->|               |
    |               |-- Verifier code appairage     |
    |               |<-- OK + UUID  |               |
    |               |               |               |
    |-- GET /bootstrap-appareil --->|               |
    |<-- Donnees initiales --------|               |
    |               |               |               |
    | (Modifications locales)      |               |
    |               |               |               |
    |-- POST /recevoir-lot -------->|               |
    | (payload modifications)      |               |
    |               |-- Verifier conflits          |
    |               |-- Appliquer modifications    |
    |<-- Resultat sync ------------|               |
    |               |               |               |
```

**Figure III.8 : Diagramme de séquence - Synchronisation**

## 6. Diagrammes d'activité

Les diagrammes d'activité modélisent les flux de contrôle et de données d'un processus.

### 6.1 Diagramme d'activité - Authentification

```
+--------+
| Debut  |
+--------+
    |
    v
+------------------+
| Saisie           |
| identifiants     |
+------------------+
    |
    v
+------------------+
| Verification     |
+------------------+
    |
    +-------+-------+
    |               |
    v               v
+------------+   +------------+
| Identif.   |   | Identif.   |
| valides    |   | invalides  |
+------------+   +------------+
    |               |
    v               v
+------------+   +------------+
| Creation   |   | Message    |
| session    |   | d'erreur   |
+------------+   +------------+
    |               |
    v               |
+------------------+ |
| Redirection      | |
| tableau de bord  | |
+------------------+ |
    |                |
    +-------+--------+
            |
            v
        +--------+
        | Fin    |
        +--------+
```

**Figure III.9 : Diagramme d'activité - Authentification**

### 6.2 Diagramme d'activité - Saisie des notes

```
+--------+
| Debut  |
+--------+
    |
    v
+------------------+
| Authentification |
| Enseignant       |
+------------------+
    |
    v
+------------------+
| Verification     |
| permissions      |
| module notes     |
+------------------+
    |
    v
+------------------+
| Selection        |
| classe + matiere |
+------------------+
    |
    v
+------------------+
| Chargement       |
| liste eleves     |
+------------------+
    |
    v
+------------------+
| Saisie notes     |
| pour chaque      |
| eleve            |
+------------------+
    |
    v
+------------------+
| Validation       |
| notes (0-20,     |
| format)          |
+------------------+
    |
    +-------+-------+
    |               |
    v               v
+------------+   +------------+
| Notes OK   |   | Erreur     |
|            |   | validation |
+------------+   +------------+
    |               |
    v               v
+------------+   +------------+
| Enreg.     |   | Message    |
| BDD        |   | d'erreur   |
+------------+   +------------+
    |               |
    v               |
+------------+      |
| Accuse     |      |
| reception  |      |
+------------+      |
    |               |
    +-------+-------+
            |
            v
        +--------+
        | Fin    |
        +--------+
```

**Figure III.10 : Diagramme d'activité - Saisie des notes**

## 7. Modèle conceptuel de données

### 7.1 Diagramme de classe global

Le diagramme de classe présente les principales entités du système et leurs relations. Il constitue le modèle conceptuel de données (MCD) sur lequel repose l'ensemble de l'application.

**Entités principales :**

1. **Ecole** : représente l'établissement scolaire (nom, logo)
2. **Classe** : groupe d'eleves avec un niveau (nom, niveau)
3. **Eleve** : apprenant inscrit dans une classe (matricule, nom, prenom, date_naissance, etc.)
4. **Professeur** : enseignant (nom, prenom, email, specialisation, diplome)
5. **Matiere** : discipline enseignee avec coefficient (nom, code)
6. **Note** : evaluation d'un eleve dans une matiere (valeur, trimestre, coefficient)
7. **Bulletin** : releve de notes periodique (moyenne, mention, appreciation)
8. **PresenceEleve** : enregistrement d'assiduite (date, session, statut)
9. **EmploiDuTemps** : planification des cours (jour, heure_debut, heure_fin)
10. **Parent** : representant legal de l'eleve (nom, prenom, lien)
11. **Utilisateur** : personne ayant acces au systeme (email, mot_de_passe, role)
12. **Permission** : droit d'acces a un module (module, acces)
13. **Paiement** : transaction financiere (montant, mois, statut)
14. **Message** : communication entre utilisateurs (contenu, type, fichier)
15. **Conversation** : groupe de discussion (type, nom)
16. **Evenement** : evenement du calendrier scolaire (titre, date_debut, date_fin)
17. **Enseignant** : variante legacy de professeur
18. **Staff** : personnel non enseignant (poste, salaire_base)
19. **Departement** : unite organisationnelle
20. **SyncDevice** : appareil connecte pour synchronisation

### 7.2 Relations principales

Les relations entre ces entités sont les suivantes :

- **Ecole** 1 --- N **Classe** : Un établissement a plusieurs classes
- **Classe** 1 --- N **Eleve** : Une classe contient plusieurs élèves
- **Classe** N --- N **Matiere** (via **ClasseMatiere**) : Une classe étudie plusieurs matières, avec des coefficients
- **Professeur** N --- N **Classe** (via **ProfesseurClasse**) : Un professeur enseigne dans plusieurs classes
- **Matiere** N --- N **ProfesseurClasse** : Une matière est enseignée par plusieurs professeurs
- **Eleve** 1 --- N **Note** : Un élève a plusieurs notes
- **Note** N --- 1 **Matiere** : Une note concerne une matière
- **Note** N --- 1 **Professeur** : Une note est attribuée par un professeur
- **Eleve** 1 --- N **Bulletin** : Un élève reçoit un bulletin par trimestre
- **Eleve** 1 --- N **PresenceEleve** : Un élève a plusieurs présences
- **Parent** N --- N **Eleve** (via **ParentEleve**) : Un parent peut avoir plusieurs enfants
- **Parent** N --- 1 **Utilisateur** : Un parent est lié à un compte utilisateur
- **Utilisateur** 1 --- N **Permission** : Un utilisateur a plusieurs permissions
- **Conversation** N --- N **Participant** : Une conversation implique plusieurs participants
- **Conversation** 1 --- N **Message** : Une conversation contient plusieurs messages
- **Message** 1 --- N **MessageReaction** : Un message peut avoir plusieurs réactions
- **Eleve** 1 --- N **Paiement** : Un élève effectue plusieurs paiements
- **TypePaiement** 1 --- N **Paiement** : Un type de paiement correspond à plusieurs transactions
- **Utilisateur** 1 --- N **Notification** : Un utilisateur reçoit plusieurs notifications
- **Professeur** 1 --- N **TeacherLesson** : Un professeur planifie plusieurs leçons
- **SyncDevice** 1 --- N **SyncBatch** : Un appareil génère plusieurs lots de sync

### 7.3 Schéma relationnel simplifié

```
ECOOLE (id, nom, logo)
  |
  +-- CLASSE (id, nom, niveau)
        |
        +-- ELEVE (id, matricule, nom, prenom, date_naissance, lieu_naissance,
        |          adresse, telephone, genre, statut, photo, qr_token, id_classe)
        |
        +-- CLASSE_MATIERE (id_classe, id_matiere, coefficient)
        |
        +-- EMPLOI_DU_TEMPS (id, jour, heure_debut, heure_fin, id_classe, id_matiere, id_professeur)
        |
        +-- PRESENCE_ELEVE (id, date_jour, session_jour, statut, scan_mode,
                            eleve_id, classe_id, annee_scolaire)

PROFESSEUR (id, nom, prenom, email, telephone, specialisation, matiere_id,
            salaire_horaire, diplome, statut)
  |
  +-- PROFESSEUR_CLASSE (id, professeur_id, classe_id, matiere_id, annee_scolaire, type)
  |
  +-- NOTE (id, valeur, trimestre, coefficient, periode, eleve_id, matiere_id, professeur_id)
  |
  +-- TEACHER_LESSON (id, titre, rubrique, date_prevue, statut, progression)
  |
  +-- TEACHER_TASK (id, titre, date_echeance, priorite, termine)

MATIERE (id, nom, code)

BULLETIN (id, trimestre, moyenne, mention, appreciation, id_eleve)

PARENT (id, nom, prenom, lien, telephone, email, adresse, profession)
  |
  +-- PARENT_ELEVE (parent_id, eleve_id)

UTILISATEUR (id, nom, email, mot_de_passe, role, avatar, last_activity)
  |
  +-- PERMISSION (utilisateur_id, module, role, acces)
  |
  +-- NOTIFICATION (user_type, user_id, titre, contenu, lue)

CONVERSATION (id, type, name, is_announcement)
  |
  +-- CONVERSATION_PARTICIPANT (conversation_id, user_type, user_id)
  |
  +-- MESSAGE (id, conversation_id, sender_type, sender_id, content, type, file_path)
        |
        +-- MESSAGE_REACTION (message_id, user_type, user_id, emoji)

TYPE_PAIEMENT (id, nom, montant, mois, classe, id_classe, date_debut, date_fin)
  |
  +-- PAIEMENT (id, type_id, personne_id, type_personne, montant, mois, statut, categorie)

STAFF (id, nom, prenom, poste, departement_id, salaire_base, statut)
  |
  +-- PRESENCE_STAFF (id, date_jour, session_jour, statut, presence, horaire)

SALLES (id, nom, numero, capacite)
  |
  +-- RESERVATION (id, date_reservation, heure_debut, heure_fin, statut)
        |
        +-- RESERVATION_RESSOURCE (reservation_id, ressource_id, quantite)

SYNC_DEVICE (id, uuid, nom, type_appareil, role_sync, plateforme, autorise)
  |
  +-- SYNC_BATCH (id, uuid, device_uuid, direction, statut, total_changements)
        |
        +-- SYNC_CHANGE (id, uuid, batch_uuid, table_name, record_uuid, operation, payload_json)
              |
              +-- SYNC_CONFLICT (id, uuid, change_uuid, table_name, record_uuid,
                                 type_conflit, resolution)
```

**Figure III.11 : Schéma relationnel simplifié**

## 8. Conclusion

Ce chapitre a présenté l'analyse et la conception du système Novaskol en utilisant le langage UML. Nous avons identifié 16 besoins fonctionnels et 10 besoins non fonctionnels, défini 7 acteurs avec leurs cas d'utilisation, modélisé les interactions dynamiques via des diagrammes de séquence et d'activité, et établi le modèle conceptuel de données avec toutes les entités et leurs relations. Cette modélisation constitue le socle sur lequel s'appuie la phase de réalisation présentée dans le chapitre suivant.

---

# Chapitre IV : Réalisation

## 1. Introduction

Ce chapitre présente la phase de réalisation de Novaskol. Nous décrivons en détail l'environnement de développement, l'implémentation du backend Laravel avec ses contrôleurs, services et middlewares, le frontend avec ses vues Blade et son design responsive, l'application de bureau Electron avec son processus de démarrage complet, les applications companion (mobile React/Capacitor et desktop Electron), le processus de packaging et de déploiement, et les interfaces principales de l'application.

## 2. Environnement de développement

### 2.1 Outils de développement

Le développement de Novaskol a été réalisé avec les outils suivants :

| Outil | Version | Utilisation |
|---|---|---|
| Visual Studio Code | Derniere | Editeur de code principal |
| PHP | 8.2.0 | Langage serveur |
| Composer | 2.x | Gestionnaire de dependances PHP |
| Node.js | 22.x | Execution JavaScript |
| npm | 10.x | Gestionnaire de paquets |
| Git | 2.x | Controle de version |
| WampServer | 3.x | Environnement PHP local (dev) |
| Electron | 37.2.1 | Framework desktop |
| electron-builder | 26.0.12 | Packaging d'installateur |
| Laravel Pint | Derniere | Correcteur de style |

**Tableau IV.1 : Outils de développement**

### 2.2 Structure du projet

L'arborescence complète du projet Novaskol est la suivante :

```
novaskol-laravel/
|
+-- app/                           # Code source Laravel
|   +-- Console/Commands/          # Commandes Artisan personnalisees
|   |   +-- NovaskolPrepareInstallerSource.php
|   +-- Exceptions/                # Gestion des exceptions
|   +-- Http/
|   |   +-- Controllers/          # 30 controleurs
|   |   |   +-- Auth/             # Authentification
|   |   |   +-- Dashboard/        # Tableau de bord
|   |   |   +-- Connected/        # Synchronisation mobile
|   |   +-- Middleware/           # 3 middlewares
|   +-- Models/                   # Modeles Eloquent
|   +-- Providers/                # Service providers
|   +-- Services/                 # 9 services metier
|
+-- apps/                          # Applications companion
|   +-- novaskol-connecte/        # Mobile React + Capacitor
|   +-- novaskol-connecte-desktop/ # Desktop companion Electron
|
+-- config/                        # Configuration Laravel
|   +-- novaskol.php              # Configuration Novaskol
|
+-- database/                      # Base de donnees
|   +-- migrations/               # 33 migrations
|   +-- seeders/                  # Donnees de test
|   +-- factories/                # Fabriques de donnees
|   +-- distribution/             # Dumps SQL (empty, demo)
|
+-- desktop/                       # Application Electron principale
|   +-- main.cjs                  # Processus principal (220 lignes)
|   +-- preload.cjs               # Pont IPC (16 lignes)
|   +-- ui/                       # Splash screen
|   |   +-- splash.html           # Interface splash (135 lignes)
|   |   +-- logo.png              # Logo
|   +-- assets/                   # Icones
|   +-- scripts/                  # Scripts post-build
|   +-- package.json              # Config electron-builder
|
+-- public/                        # Fichiers publics
|   +-- legacy/                   # Assets herites
|
+-- resources/                     # Vue Blade, CSS, JS
|   +-- views/                    # 66 templates Blade
|   |   +-- auth/                 # Pages d'authentification
|   |   +-- dashboard/            # Tableaux de bord
|   |   +-- installation/         # Assistant installation
|   |   +-- modules/              # Modules fonctionnels
|   |   |   +-- accounting/       # Comptabilite
|   |   |   +-- administration/   # Administration
|   |   |   +-- communication/    # Messagerie
|   |   |   +-- parametres/       # Parametres
|   |   |   +-- pedagogique/      # Pedagogie
|   |   |   +-- professeur/       # Notes, bulletins
|   |   |   +-- reports/          # Rapports
|   |   |   +-- rh/              # Ressources humaines
|   |   +-- parents/             # Portail parent
|   |   +-- partials/            # Partiels reutilisables
|   |   +-- teacher/             # Espace enseignant
|
+-- routes/                        # Definition des routes
|   +-- web.php                   # 287 lignes de routes
|   +-- console.php               # Commandes Artisan
|
+-- storage/                       # Stockage
|   +-- app/
|   |   +-- distribution/         # Paquets de distribution
|   |   +-- desktop-dist/         # Builds Electron
|   +-- logs/                     # Logs Laravel
|
+-- tools/                         # Outils de build et runtime
|   +-- runtime/
|   |   +-- php/                  # PHP 8.2 portable
|   |   +-- mysql/                # MySQL 8.0 portable
|   +-- windows/                  # Scripts PowerShell
|   |   +-- Build-Novaskol-Desktop.ps1
|   |   +-- Prepare-Novaskol-Local.ps1
|   |   +-- Start-Novaskol.ps1 (485 lignes)
|   |   +-- Stop-Novaskol.ps1
|   |   +-- Start-Novaskol-Database.ps1
|   |   +-- Stop-Novaskol-Database.ps1
|   |   +-- Lancer-Novaskol.cmd / .vbs
|   +-- installer/
|       +-- inno/                 # Scripts Inno Setup
|       +-- build-installer-assets.ps1
|
+-- vendor/                        # Dependances PHP (Composer)
+-- node_modules/                  # Dependances Node.js
+-- main.cjs                       # Electron main process (racine)
+-- composer.json                  # Dependances PHP
+-- package.json                   # Dependances Node.js + Vite
+-- vite.config.js                 # Configuration Vite
+-- router.php                     # Routeur PHP (dev server)
```

**Tableau IV.2 : Arborescence complète du projet**

### 2.3 Processus de développement

Le développement a suivi une approche itérative avec des cycles de 2 semaines :

1. **Phase d'analyse** : Recueil des besoins, specifications
2. **Phase de conception** : Modelisation UML, architecture technique
3. **Phase d'implementation** : Codage des modules fonctionnels
4. **Phase de test** : Tests unitaires, tests d'integration
5. **Phase de validation** : Tests utilisateurs, corrections
6. **Phase de packaging** : Build, deploiement, documentation

## 3. Implémentation du backend Laravel

### 3.1 Architecture des contrôleurs

Les contrôleurs sont organisés par domaine fonctionnel. Novaskol compte 30 fichiers de contrôleurs répartis comme suit :

```
app/Http/Controllers/
+-- Auth/
|   +-- LegacyAuthController.php          # Authentification legacy
+-- Dashboard/
|   +-- DashboardController.php           # Tableau de bord
|   +-- RoleDashboardController.php       # Dashboard par role
+-- Connected/
|   +-- ConnectedInitController.php       # Initialisation connecte
|   +-- ConnectedSyncController.php       # Synchronisation connecte
+-- AccountingController.php              # Comptabilite
+-- BulletinController.php                # Bulletins de notes
+-- ClassroomController.php               # Gestion des classes
+-- CommunicationController.php           # Messagerie
+-- ExamBlankController.php               # Examens blancs
+-- GradeController.php                   # Notes
+-- HumanResourceController.php           # Ressources humaines
+-- InstallationController.php            # Assistant installation
+-- ParentPortalController.php            # Portail parent
+-- PedagogyController.php                # Gestion pedagogique
+-- ProfileController.php                 # Profil utilisateur
+-- ReportController.php                  # Rapports
+-- ResultController.php                  # Resultats
+-- ScheduleController.php                # Emploi du temps
+-- SchoolController.php                  # Informations ecole
+-- SettingsController.php                # Parametres
+-- StudentController.php                 # Eleves
+-- SubjectController.php                 # Matieres
+-- TeacherWorkspaceController.php        # Espace enseignant
+-- UserAccountController.php             # Comptes utilisateurs
| (ACLController.php, ContactController.php, ...)
```

**Tableau IV.3 : Liste des contrôleurs**

### 3.2 Système d'authentification

Le système d'authentification est basé sur les sessions PHP et la table `utilisateurs`. Contrairement à l'authentification Laravel standard (scaffold Breeze/Jetstream), Novaskol utilise un système personnalisé développé spécifiquement pour le projet.

**Processus d'authentification :**

1. L'utilisateur soumet le formulaire de connexion avec email, mot de passe et rôle
2. Le contrôleur interroge la table `utilisateurs` avec l'email et le rôle fournis
3. Le mot de passe est vérifié à l'aide de `Hash::check()`
4. Si les identifiants sont valides :
   - Les informations utilisateur sont stockées en session (`utilisateur_id`, `utilisateur_nom`, `utilisateur_email`, `utilisateur_role`)
   - Si le rôle est 'admin', les permissions administrateur sont accordées
   - L'utilisateur est redirigé vers `/dashboard`
5. Si les identifiants sont invalides, un message d'erreur est affiché

```php
// Verification des identifiants (pseudo-code)
$user = DB::table('utilisateurs')
    ->where('email', $request->email)
    ->where('role', $request->role)
    ->first();

if ($user && Hash::check($request->password, $user->password)) {
    session([
        'utilisateur_id'    => $user->id,
        'utilisateur_nom'   => $user->nom,
        'utilisateur_email' => $user->email,
        'utilisateur_role'  => $user->role
    ]);
    return redirect('/dashboard');
}
```

### 3.3 Middleware de contrôle d'accès

Le middleware `module.access` est le mécanisme central de contrôle d'accès dans Novaskol. Chaque route est protégée par ce middleware avec un paramètre spécifiant le module concerné.

**Fonctionnement :**
- Le middleware reçoit le nom du module en paramètre
- Il vérifie si l'utilisateur connecté a une permission avec `acces = true` pour ce module
- Si la vérification échoue, une réponse HTTP 403 (Accès non autorisé) est renvoyée
- Si la vérification réussit, la requête est transmise au contrôleur

```php
// Middleware module.access (pseudo-code)
$permission = DB::table('permissions')
    ->where('utilisateur_id', session('utilisateur_id'))
    ->where('module', $module)
    ->where('acces', true)
    ->exists();

if (!$permission) {
    abort(403, 'Acces non autorise');
}
```

**Exemples d'utilisation dans les routes :**
```php
Route::get('/notes', [GradeController::class, 'index'])
    ->middleware('module.access:notes');

Route::get('/bulletin', [BulletinController::class, 'index'])
    ->middleware('module.access:bulletin');
```

### 3.4 Services métier

Les services métier encapsulent la logique complexe de l'application dans des classes dédiées :

- **BulletinCalculator** : Calcul des moyennes par matière, moyenne générale, rang, mention
- **QrCodeService** : Génération de QR codes pour les cartes d'identification (via chillerlan/php-qrcode)
- **ConnectedLocalSynchronizer** : Synchronisation bidirectionnelle entre appareils
- **ModuleRegistry** : Enregistrement et navigation des modules
- **RelationalDeleteService** : Suppression en cascade avec gestion des dépendances
- **SyncService** : Gestion des lots de synchronisation, résolution de conflits
- **ExportService** : Export des données (Excel, PDF)

### 3.5 Génération de PDF

Les bulletins et reçus sont générés en PDF via la bibliothèque DOMPDF (barryvdh/laravel-dompdf) :

```php
$pdf = Pdf::loadView('modules.professeur.bulletin.pdf', [
    'eleve'    => $eleve,
    'notes'    => $notes,
    'moyennes' => $moyennes,
    'trimestre' => $trimestre
]);
return $pdf->download("bulletin-{$eleve->mat_etud}.pdf");
```

### 3.6 Routes API

Le fichier `routes/web.php` contient 287 lignes définissant l'ensemble des routes de l'application, organisées en :

- **Routes publiques** : Authentification, installation, pages légales
- **Routes protégées** : Chaque module avec son middleware d'accès
- **Routes API de synchronisation** : Points d'accès REST pour les appareils connectés
- **Routes de l'espace parent** : Portail parent dédié
- **Routes de l'espace enseignant** : Plans de cours et tâches

## 4. Implémentation du frontend

### 4.1 Architecture des vues

Le frontend utilise le moteur de templates Blade de Laravel avec 66 fichiers de vue organisés par module fonctionnel. Chaque module dispose de ses propres vues, généralement une par action (index, create, edit, show).

### 4.2 Design et thème

- **Theme sombre** par défaut avec mode clair activable
- **Palette de couleurs** : Fond sombre (#080e18), accent vert (#00c853), avec nuances
- **CSS personnalise** via variables CSS (`:root`)
- **Design responsive** grace a TailwindCSS
- **Animations** avec Intersection Observer et transitions CSS
- **Notifications** SweetAlert2 pour les interactions utilisateur

### 4.3 Composants d'interface

- **Barre laterale** : Navigation principale avec sous-menus repliables, categorisation par module
- **En-tete fixe** : Notifications, selection langue, profil utilisateur, bascule theme
- **Tableau de bord** : Widgets statistiques, graphiques Chart.js, calendrier evenements
- **Tableaux de donnees** : Listes avec recherche, tri, pagination
- **Formulaires** : Saisie avec validation client et serveur
- **Cartes d'identite** : Generation visuelle de cartes avec QR code integre
- **Calendrier** : FullCalendar pour les evenements et emplois du temps
- **Chat** : Interface de messagerie avec reactions, fichiers

### 4.4 Internationalisation

Le systeme supporte 6 langues :
- Francais
- Anglais
- Allemand
- Malgache
- Espagnol
- Portugais

## 5. Application de bureau Electron

### 5.1 Architecture Electron

L'application de bureau Novaskol Desktop est le composant central du système. Elle est construite avec Electron 37 et embarque l'ensemble du serveur PHP et de Laravel dans une application Windows autonome.

**Fichiers cles :**

| Fichier | Taille | Role |
|---|---|---|
| `desktop/main.cjs` | 220 lignes | Processus principal Electron |
| `desktop/preload.cjs` | 16 lignes | Pont IPC securise |
| `desktop/ui/splash.html` | 135 lignes | Interface de demarrage |
| `desktop/package.json` | 51 lignes | Configuration build |

### 5.2 Processus principal (main.cjs)

Le processus principal gère l'intégralité du cycle de vie de l'application :

```javascript
async function startNovaskol(r) {
    // 1. Configurer l'environnement
    ensureLayout(r);        // Cree la structure de dossiers Laravel
    configureEnv(r);        // Configure .env avec SQLite, production
    ensureAppKey(r);        // Genere APP_KEY si absent

    // 2. Installer les dependances si necessaire
    if (!fs.existsSync(path.join(r, 'vendor'))) {
        runComposerInstall(r);
    }

    // 3. Executer les migrations
    runPhpArtisan(r, 'migrate --force');

    // 4. Demarrer le serveur PHP
    phpProcess = spawn(getPhp(r), [
        path.join(r, 'artisan'),
        'serve', '--host=0.0.0.0', '--port=8001'
    ], { cwd: r, windowsHide: true });

    // 5. Attendre que le serveur reponde
    await waitForHttp('http://127.0.0.1:8001', 120000);

    // 6. Ouvrir la fenetre principale
    mainWindow.loadURL('http://127.0.0.1:8001');
}
```

**Fonctionnalites du processus principal :**

1. **Single-instance lock** : `app.requestSingleInstanceLock()` empeche le lancement multiple
2. **Resolution du runtime PHP** : 4 strategies (packaged, distribution, env vars, PATH)
3. **Fenetre splash** : 460x360, frameless, theme sombre, logo anime
4. **Fenetre principale** : 1440x920 (min 1220x760), sans menu
5. **Configuration .env** automatique : SQLite, production, session fichier
6. **Migration forcee** : `php artisan migrate --force` au demarrage
7. **Serveur PHP** : `php artisan serve --host=0.0.0.0 --port=8001`
8. **Health check** : Ping HTTP jusqu'a 120 secondes
9. **Fermeture** : Confirmation utilisateur, arret du processus PHP

### 5.3 Pont IPC (preload.cjs)

Le pont IPC expose des API securisees au processus de rendu via `contextBridge` :

```javascript
contextBridge.exposeInMainWorld('desktopShell', {
    onSplashStatus(callback) {
        ipcRenderer.on('splash-status', (_event, message) => callback(message));
    },
    async getMeta() {
        return ipcRenderer.invoke('desktop:get-meta');
    },
    print() {
        ipcRenderer.send('print');
    },
    printHtml(html) {
        ipcRenderer.send('print-html', html);
    }
});
```

### 5.4 Configuration electron-builder

La configuration de build est definie dans `desktop/package.json` :

```json
{
    "build": {
        "appId": "com.novaskol.desktop",
        "productName": "Novaskol",
        "directories": {
            "output": "../storage/app/desktop-dist"
        },
        "extraResources": [
            {
                "from": "../storage/app/distribution/novaskol-app-latest",
                "to": "seed/novaskol",
                "filter": ["**/*"]
            }
        ],
        "win": { "target": ["nsis"] },
        "nsis": {
            "oneClick": false,
            "allowToChangeInstallationDirectory": true,
            "createDesktopShortcut": true,
            "createStartMenuShortcut": true
        }
    }
}
```

**Tableau IV.4 : Configuration electron-builder**

### 5.5 Scripts de build et déploiement

Les scripts PowerShell dans `tools/windows/` automatisent l'ensemble du processus de build et de déploiement :

**Build-Novaskol-Desktop.ps1 :**
1. Resout le chemin de PHP
2. Execute `php artisan novaskol:prepare-installer-source --with-vendor`
3. Nettoie l'ancienne distribution
4. Execute `npm install` dans `desktop/`
5. Execute `npm run build` (electron-builder)

**Build-Novaskol-Runtime.ps1 :**
1. Copie PHP portable depuis WAMP vers `tools/runtime/php/`
2. Copie les executables MySQL vers `tools/runtime/mysql/bin/`
3. Patiente le php.ini (extension_dir relative, opcache)

## 6. Application mobile companion (Novaskol Connecte)

### 6.1 Architecture

Novaskol Connecte est une application mobile hybride développée avec React 19, Vite 8, et Capacitor 8 pour Android. Elle fonctionne également comme PWA (Progressive Web App) pour une utilisation via navigateur.

**Caractéristiques techniques :**
- **Version** : 0.2.0
- **Framework** : React 19 (SPA single-file, 2178 lignes)
- **Build** : Vite 8
- **Natiff** : Capacitor 8.3.3 (Android)
- **Offline** : Service Worker + localStorage
- **Stockage** : IndexedDB via localStorage keys

### 6.2 Fonctionnalités

- Consultation des notes et bulletins
- Suivi des presences
- Messagerie
- Calendrier scolaire
- Gestion des eleves (CRUD)
- Calcul de bulletins cote client
- Synchronisation hors-ligne
- Appairage par QR code

### 6.3 Processus d'appairage

L'application mobile suit un processus d'appairage en 3 étapes :

1. **Etape 1** : Saisie de l'URL du serveur, du code d'appairage, et du nom de l'appareil
2. **Etape 2** : Selection du role et authentification (email + mot de passe)
3. **Etape 3** : Resumé et validation avant initialisation

Une fois appairée, l'application peut fonctionner en mode hors-ligne avec une file d'attente de modifications synchronisées périodiquement.

### 6.4 Calcul des bulletins (côté client)

L'application mobile implémente le calcul des bulletins entièrement côté client :

- Periodes : B1/B2 (bimestre), T1/T2/T3 (trimestre)
- Moyennes ponderees par coefficient
- Calcul du rang par matiere et general
- Mapping note-mention

## 7. Application de bureau Connecte Desktop

### 7.1 Architecture

Novaskol Connecte Desktop est une application Electron companion pour PC secondaire. Contrairement à la version mobile, elle embarque son propre serveur PHP/Laravel (seed complet).

**Caractéristiques techniques :**
- **Version** : 1.0.0
- **Framework** : Electron 37.2.1
- **Backend** : PHP 8.2 + Laravel embarque
- **Processus principal** : 789 lignes
- **Autosync** : Toutes les 5 minutes

### 7.2 Différences avec la version mobile

| Aspect | Connecte Mobile | Connecte Desktop |
|---|---|---|
| Technologie | React 19 + Capacitor | Electron + PHP embarque |
| Backend | Externe (HTTP) | Local (PHP serveur) |
| Stockage | localStorage | SQLite locale |
| Sync | Push/Pull HTTP | PHP + sync automatique |
| Autonomie | Necessite serveur principal | Autonome (seed Laravel) |

### 7.3 Processus de démarrage

1. Lancement de l'executable
2. Affichage du splash screen
3. Initialisation du PHP et de Laravel (seed)
4. Configuration du .env avec `NOVASKOL_EDITION=connecte`
5. Execution des migrations
6. Demarrage du serveur PHP sur le port 8002
7. Chargement de l'interface `/connected/init`

## 8. Processus de packaging et déploiement

### 8.1 Application de bureau (Electron + electron-builder)

Le processus de build et packaging de l'application de bureau suit les étapes suivantes :

1. **Preparation** : `php artisan novaskol:prepare-installer-source --with-vendor`
   - Copie les fichiers essentiels (app/, config/, database/, etc.)
   - Exclut les fichiers de dev (node_modules, .git, etc.)
   - Genere le dossier `storage/app/distribution/novaskol-app-latest/`

2. **Copie du runtime** : PHP portable + extensions + MySQL portable
   - `tools/runtime/` copie vers `distribution/novaskol-app-latest/tools/runtime/`

3. **Installation des dependances npm** : `npm install` dans `desktop/`

4. **Build Electron** : `npm run build` (electron-builder --win nsis)
   - Compile l'application Electron
   - Integre les extraResources (seed/novaskol)
   - Genere l'installateur NSIS

5. **Resultat** : `storage/app/desktop-dist/Novaskol Setup 1.0.6.exe` (~647 Mo)

### 8.2 PHP Runtime portable

Le runtime PHP est pre-packagé dans l'installateur. Sa configuration est optimisée pour le mode desktop :

- **PHP 8.2.0** avec 19 extensions chargees
- **extension_dir="ext"** (chemin relatif)
- **opcache** active avec JIT tracing (mode production)
- **memory_limit=128M**
- **max_execution_time=120**

### 8.3 Installateur NSIS

L'installateur Windows, genere par electron-builder, offre :

- Assistant d'installation graphique
- Choix du repertoire d'installation (par defaut: %LOCALAPPDATA%\Programs\Novaskol)
- Raccourcis Bureau et Menu Demarrer
- Installation silencieuse possible
- Desinstallation propre (suppression cache, sessions, vues compilees)
- Icone personnalisee (novaskol.ico)

### 8.4 Modes de déploiement

| Mode | Description | Base de donnees | Installation |
|---|---|---|---|
| Local (Desktop) | Application autonome avec PHP embarque | SQLite | Installateur NSIS |
| Hebergement | Serveur web Apache/Nginx | MySQL/MariaDB | Deploiement FTP/CLI |
| Hybride | Desktop + Appareils connectes | SQLite + Sync reseau | Desktop + Connecte |

## 9. Interfaces principales

### 9.1 Page d'authentification

La page d'accueil de Novaskol presente :

- Le logo et le nom de l'application
- Un formulaire de connexion avec selection du role (administration, enseignant, personnel, parent)
- Des sections d'information (A propos, Espaces)
- Les fonctionnalites principales
- Un formulaire de contact
- Options multilingues (6 langues)

### 9.2 Tableau de bord

Le tableau de bord principal offre une vue d'ensemble de l'etablissement :

- Widgets statistiques : nombre d'eleves, enseignants, classes
- Graphiques d'evolution (Chart.js)
- Evenements du calendrier a venir
- Notifications recentes
- Acces rapide aux modules frequents
- Bascule theme clair/sombre

### 9.3 Saisie des notes

L'interface de saisie des notes permet :

- Selection de la classe et de la matiere
- Affichage de la liste des eleves avec notes existantes
- Saisie des notes avec validation automatique (0-20)
- Calcul automatique des moyennes
- Enregistrement par trimestre
- Gestion des coefficients

### 9.4 Génération de bulletins

L'interface de bulletins offre :

- Selection de l'eleve, de la classe, du trimestre
- Calcul automatique des moyennes par matiere
- Calcul de la moyenne generale et du rang
- Mention et appreciation automatiques
- Export PDF (via DOMPDF)
- Bulletin individuel, par classe, annuel

### 9.5 Emploi du temps

L'interface d'emploi du temps presente :

- Vue hebdomadaire par classe
- Affectation des matieres et professeurs
- Gestion des creneaux horaires (heure_debut, heure_fin)
- Integration FullCalendar

### 9.6 Messagerie interne

L'interface de messagerie permet :

- Conversations privees et de groupe
- Envoi de messages textes et fichiers
- Reactions par emojis
- Indicateurs de lecture (is_read, is_delivered)
- Statut de saisie (typing_status)
- Messagerie d'annonces (is_announcement)

### 9.7 Gestion des présences par QR code

L'interface de pointage QR permet :

- Generation de cartes d'identification avec QR code
- Scan de QR code sur les cartes
- Enregistrement automatique de la presence
- Sessions matin et apres-midi
- Types de scan : entree / sortie
- Statistiques de presence
- Export des rapports

## 10. Conclusion

Ce chapitre a presente la realisation complete de Novaskol. L'implementation couvre l'ensemble des 45 modules fonctionnels a travers une architecture modulaire et evolutive. Le backend Laravel avec ses 30 controleurs, 9 services et 3 middlewares constitue le cceur du systeme. Le frontend offre 66 vues Blade avec un design moderne et responsive. L'application de bureau Electron permet un deploiement simplifie et un fonctionnement completement hors-ligne. Les applications companion (mobile et desktop) etendent l'acces aux utilisateurs mobiles et aux postes secondaires. Le packaging via electron-builder produit un installateur Windows autonome de 647 Mo contenant l'integralite des composants necessaires.

---

## Conclusion generale

Le travail presente dans ce memoire avait pour objectif la conception et la realisation d'un systeme de gestion scolaire complet, fonctionnant hors-ligne, facile a deployer et a utiliser. Nous avons concu et developpe **Novaskol**, une application qui repond aux besoins specifiques des etablissements scolaires en matiere de gestion administrative, pedagogique et financiere.

### Resultats obtenus

Au terme de ce projet, nous avons atteint les objectifs fixés :

1. **Un systeme complet** : 45 modules fonctionnels couvrant l'ensemble des besoins de gestion scolaire (inscriptions, notes, bulletins, presences, emplois du temps, communication, finances, RH, synchronisation, etc.)

2. **Une architecture robuste** : Basee sur Laravel 12 et PHP 8.2 avec Eloquent ORM, architecture MVC, middlewares de controle d'acces, et services metier specialises.

3. **Une base de donnees exhaustive** : 42 tables couvrant l'ensemble des domaines fonctionnels, avec 33 migrations Laravel pour le versionnement du schema.

4. **Une application de bureau autonome** : Grace a Electron 37, Novaskol s'installe et s'execute sur n'importe quel PC Windows sans necessite de serveur dedie ni de connexion Internet. L'installateur NSIS de 647 Mo contient PHP 8.2, Laravel, et SQLite.

5. **Deux applications companion** : Novaskol Connecte (mobile, React+Capacitor) et Novaskol Connecte Desktop (Electron) permettent aux utilisateurs mobiles et aux postes secondaires de travailler en collaboration.

6. **Une synchronisation multi-appareils** : Le systeme de synchronisation sur reseau local avec appairage, files d'attente hors-ligne, et resolution de conflits permet un travail collaboratif fluide.

7. **Une interface intuitive** : Adaptee a chaque profil d'utilisateur (admin, enseignant, parent, staff) avec un design moderne, theme sombre/clair, et support multilingue (6 langues).

8. **Un deploiement simplifie** : L'installateur Windows unique contient l'ensemble des composants necessaires (PHP, Laravel, extensions, base de donnees).

### Difficultes rencontrees

La realisation de ce projet n'a pas ete sans difficultes. Parmi les principaux defis :

- **Integration de PHP dans Electron** : Le lancement et la gestion du cycle de vie du serveur PHP depuis Electron a necessite une gestion fine des processus fils (spawn), des chemins d'acces, et des droits utilisateur.

- **Packaging du runtime PHP** : La creation d'un installateur incluant PHP 8.2 avec ses 19 extensions, MySQL portable, et l'ensemble des dependances Laravel, a exige une configuration precise du php.ini (extension_dir relatif, opcache, chemins absolus vs relatifs).

- **Synchronisation multi-appareils** : La conception d'un mecanisme de synchronisation bidirectionnelle robuste avec UUIDs globaux, detection de conflits, et resolution a ete complexe.

- **Migration du code legacy** : Une partie du code existait sous forme de scripts PHP non-Laravel (base MySQL `bulletin_system`). La migration progressive vers l'architecture Laravel a necessite une phase d'adaptation et de transformation des donnees.

- **Gestion des chemins dans php.ini** : Les chemins absolus (extension_dir, error_log, upload_tmp_dir, session.save_path) ont cause des problemes de portabilite entre machines de developpement et machines cibles.

### Compétences acquises

Ce projet m'a permis d'acquerir et de renforcer des competences cles, en prolongement de mon parcours CAN et de ma specialisation web :

- **Developpement web full-stack** : Laravel (MVC, Eloquent ORM, migrations, Blade), PHP oriente objet, React 19
- **Applications de bureau hybrides** : Electron, IPC, gestion de processus, packaging via electron-builder
- **Applications mobiles** : React 19, Capacitor 8, PWA, IndexedDB, synchronisation hors-ligne
- **Bases de donnees** : SQLite, MySQL/MariaDB, conception de schemas, optimisation de requetes
- **Modelisation et conception** : UML (cas d'utilisation, diagrammes de sequence, d'activite, de classes)
- **Packaging et deploiement** : electron-builder, NSIS, configuration d'installateur, runtime PHP portable
- **Scripting et automatisation** : PowerShell, gestion des processus, configuration systeme
- **Gestion de projet** : Planification, methodologie agile, documentation, versionnement Git
- **Integration des technologies** : Capacite a assembler des briques technologiques diverses (PHP, Node.js, React, SQLite, Electron) en un produit coherent et fonctionnel

### Perspectives d'evolution

Plusieurs ameliorations et extensions peuvent etre envisagees :

1. **Application mobile native** : Developper des applications iOS et Android natives avec React Native ou Flutter, avec fonctionnalites etendues (notifications push, biometric, camera).

2. **Version cloud SaaS** : Deployer une version centralisee avec hebergement cloud et abonnement, permettant la gestion de plusieurs etablissements.

3. **Synchronisation Internet** : Permettre la synchronisation entre etablissements distants via Internet avec chiffrement de bout en bout.

4. **Intelligence artificielle** : Integrer des modules d'IA pour la prediction des resultats scolaires, la detection precoce des difficultes, et la generation de recommandations personnalisees.

5. **API publique** : Exposer une API REST publique documentee pour permettre l'integration avec des systemes tiers (ENT, logiciels de cantine, bibliotheque).

6. **Modules complementaires** : Gestion de la bibliotheque, restauration scolaire, transport scolaire, infirmerie, gestion des stages.

7. **Compatibilite multi-plateforme** : Etendre l'application de bureau a macOS et Linux (electron-builder supporte ces cibles).

8. **Tableaux de bord avances** : Analytics avec tableaux de bord personnalisables, exports automatises, et visualisations interactives (grafana, metabase).

9. **Mode multi-etablissement** : Permettre la gestion de plusieurs etablissements depuis une meme installation.

10. **Signature electronique** : Integration de signature electronique pour les bulletins et documents officiels.

### Mot de la fin

La realisation de ce projet a ete pour moi bien plus qu'un exercice academique. Issu d'un parcours en Communication en Audiovisuelle et Numerique (CAN), je me suis tourne vers le web apres la L3, anime par l'envie de comprendre et de maitriser les technologies qui faconnent notre monde numerique. Novaskol represente l'aboutissement de cette transition : un systeme complet, du dossier de conception a l'installateur final, en passant par la base de donnees, le code et l'interface utilisateur.

J'espere que cet outil contribuera a ameliorer la gestion et la qualite de l'enseignement dans les etablissements qui l'adopteront. Mais au-dela de l'outil lui-meme, ce projet restera pour moi la trace tangible d'un parcours, d'un choix et d'une volonte de laisser une empreinte dans le domaine qui me passionne : le web.

Novaskol est un projet open source (licence MIT), dont le code source est librement accessible et modifiable. La communaute est encouragee a contribuer a son amelioration continue.

---

## Bibliographie

### Ouvrages et articles

[1] Kruchten, P. (1995). "4+1 View Model of Software Architecture." IEEE Software, 12(6), 42-50.

[2] Gamma, E., Helm, R., Johnson, R., & Vlissides, J. (1994). "Design Patterns: Elements of Reusable Object-Oriented Software." Addison-Wesley.

[3] Booch, G., Rumbaugh, J., & Jacobson, I. (2005). "The Unified Modeling Language User Guide." 2nd Edition. Addison-Wesley.

[4] Roques, P. (2004). "UML par la pratique." 2e edition. Editions Eyrolles.

[5] Fowler, M. (2002). "Patterns of Enterprise Application Architecture." Addison-Wesley.

[6] Gardarin, G. (2000). "Internet/Intranet et les bases de donnees." Editions Eyrolles.

[7] Comer, D. (2003). "Internet : Services et reseaux." Editions Dunod.

[8] Otlet, J. (2002). "Architectures de Systemes d'Information." Livre Blanc OCTO.

[9] Ullman, L. (2023). "PHP for the Web: Visual QuickStart Guide." 5th Edition. Peachpit Press.

### Documentation technique

[10] Laravel Documentation. "Laravel 12.x Documentation." https://laravel.com/docs/12.x

[11] PHP Documentation. "PHP Manual." https://www.php.net/docs.php

[12] Electron Documentation. "Electron Documentation." https://www.electronjs.org/docs

[13] electron-builder Documentation. "electron-builder Documentation." https://www.electron.build/

[14] SQLite Documentation. "SQLite Documentation." https://www.sqlite.org/docs.html

[15] TailwindCSS Documentation. "TailwindCSS v4 Documentation." https://tailwindcss.com/docs

[16] React Documentation. "React 19 Documentation." https://react.dev/

[17] Capacitor Documentation. "Capacitor 8 Documentation." https://capacitorjs.com/docs

[18] MySQL Documentation. "MySQL 8.0 Documentation." https://dev.mysql.com/doc/

[19] DOMPDF. "Laravel DOMPDF." https://github.com/barryvdh/laravel-dompdf

[20] PhpSpreadsheet. "PhpSpreadsheet Documentation." https://phpspreadsheet.readthedocs.io/

### References web

[21] Wikipedia. "Architecture logicielle." https://fr.wikipedia.org/wiki/Architecture_logicielle

[22] Wikipedia. "Client-serveur." https://fr.wikipedia.org/wiki/Client-serveur

[23] Wikipedia. "PHP." https://fr.wikipedia.org/wiki/PHP

[24] Wikipedia. "Laravel." https://fr.wikipedia.org/wiki/Laravel

[25] Wikipedia. "Electron." https://fr.wikipedia.org/wiki/Electron_(framework)

[26] Wikipedia. "UML." https://fr.wikipedia.org/wiki/UML_(informatique)

[27] Wikipedia. "SQLite." https://fr.wikipedia.org/wiki/SQLite

[28] Wikipedia. "Modele-Vue-Controleur." https://fr.wikipedia.org/wiki/Modèle-Vue-Contrôleur

---

## Annexes

### Annexe A : Generalites sur les reseaux informatiques

#### A.1 Definition d'un reseau

Un reseau informatique est un ensemble d'equipements informatiques relies entre eux pour echanger des informations. Les reseaux permettent le partage de ressources, la communication entre utilisateurs, et l'acces a des services centralises.

#### A.2 Interets d'un reseau informatique

- Partage de fichiers et de peripheriques (imprimantes, scanners)
- Communication entre utilisateurs (messagerie, chat)
- Centralisation des donnees (serveurs de fichiers, bases de donnees)
- Acces a Internet
- Travail collaboratif

#### A.3 Classification des reseaux

- **LAN (Local Area Network)** : Reseau local, couvre une zone limitee (etablissement scolaire, entreprise)
- **MAN (Metropolitan Area Network)** : Reseau metropolitain, couvre une ville
- **WAN (Wide Area Network)** : Reseau etendu, couvre un pays ou le monde

#### A.4 Topologies de reseau

- **Topologie en bus** : Tous les equipements sont connectes a un câble commun
- **Topologie en anneau** : Chaque equipement est connecte a ses deux voisins
- **Topologie en etoile** : Tous les equipements sont connectes a un commutateur central (topologie la plus courante)

#### A.5 Modele OSI

Le modele OSI (Open Systems Interconnection) definit 7 couches de communication :

1. **Physique** : Transmission des bits sur le support
2. **Liaison de donnees** : Acces au support, detection d'erreurs
3. **Reseau** : Routage des paquets (IP)
4. **Transport** : Transport fiable des donnees (TCP)
5. **Session** : Gestion des sessions de communication
6. **Presentation** : Codage et decodage des donnees
7. **Application** : Interfaces avec les applications utilisateur (HTTP, FTP, SMTP)

#### A.6 Protocole TCP/IP

TCP/IP est le protocole fondamental d'Internet. Il combine :
- **IP (Internet Protocol)** : Routage des paquets entre les machines
- **TCP (Transmission Control Protocol)** : Transport fiable avec controle de flux et correction d'erreurs

### Annexe B : UML — Unified Modeling Language

#### B.1 Definition

UML (Unified Modeling Language) est un langage de modelisation graphique utilise en genie logiciel pour visualiser, specifier, construire et documenter les systemes d'information.

#### B.2 Diagrammes UML utilises dans Novaskol

**Diagramme de cas d'utilisation :** Represente les fonctionnalites du systeme du point de vue des acteurs externes. Utilise pour definir le perimetre fonctionnel.

**Diagramme de sequence :** Montre les interactions entre objets au cours du temps. Utilise pour modeliser les processus d'authentification, saisie de notes, generation de bulletins.

**Diagramme d'activite :** Represente les flux de controle et de donnees. Utilise pour modeliser les processus metier (saisie notes, validation).

**Diagramme de classes :** Montre la structure statique du systeme (classes, attributs, methodes, relations). Utilise pour modeliser le schema conceptuel de la base de donnees.

#### B.3 Elements d'UML

- **Acteur** : Entite qui interagit avec le systeme (utilisateur, systeme externe)
- **Cas d'utilisation** : Fonctionnalite offerte par le systeme
- **Classe** : Abstraction d'une entite du domaine (Eleve, Professeur, Note)
- **Relation** : Association, inheritance, dependance, realisation
- **Message** : Communication entre objets (synchrone, asynchrone)
- **Etat** : Condition dans laquelle se trouve un objet

### Annexe C : Guide d'installation de Novaskol

#### C.1 Installation locale (Desktop)

1. Telecharger `Novaskol-Setup-1.0.6.exe` (647 Mo)
2. Executer l'installateur
3. Suivre l'assistant d'installation (choix du repertoire, creation des raccourcis)
4. Lancer Novaskol depuis le raccourci Bureau ou Menu Demarrer
5. Au premier demarrage, l'application initialise automatiquement la base de donnees
6. Creer un compte administrateur via le formulaire d'inscription
7. Configurer l'etablissement (nom, logo)

#### C.2 Configuration minimale requise

- Systeme d'exploitation : Windows 10/11 64-bit
- Processeur : Intel Core i3 ou equivalent
- RAM : 4 Go minimum (8 Go recommande)
- Espace disque : 2 Go disponibles
- Aucune connexion Internet requise

#### C.3 Installation sur hebergement web

1. Deployer les fichiers du projet sur le serveur web
2. Configurer le fichier `.env` avec les parametres de la base de donnees MySQL
3. Executer `php artisan migrate --force`
4. Executer `php artisan key:generate`
5. Configurer le serveur web (Apache/Nginx) pour pointer vers `public/`
6. Creer un compte administrateur

### Annexe D : Structure du paquet de distribution

Le paquet de distribution `novaskol-app-latest/` contient l'ensemble des fichiers necessaires au fonctionnement de l'application :

```
novaskol-app-latest/
+-- app/                    # Code Laravel (Modeles, Controleurs, Services)
+-- bootstrap/              # Bootstrap Laravel
+-- config/                 # Fichiers de configuration
+-- database/               # Migrations et dumps
+-- public/                 # Point d'entree web
|   +-- legacy/            # Assets herites
+-- resources/              # Vues Blade et assets
|   +-- views/             # 66 templates Blade
+-- routes/                 # Routes de l'application
|   +-- web.php            # 287 lignes de routes
+-- storage/                # Stockage (logs, cache, uploads)
+-- tools/                  # Outils
|   +-- runtime/           # PHP portable + MySQL portable
|       +-- php/           # PHP 8.2.0 avec 19 extensions
|       +-- mysql/         # MySQL 8.0.31 portable
+-- vendor/                 # Dependances PHP (Composer)
+-- main.cjs                # Processus principal Electron
+-- preload.cjs             # Pont IPC
+-- composer.json           # Definition des dependances PHP
+-- package.json            # Definition des dependances Node.js
```

### Annexe E : Tableau des routes API

Les points d'acces API REST pour la synchronisation des appareils connectes :

| Methode | Route | Description |
|---|---|---|
| GET | /reseau-local/ping | Test de connexion au serveur |
| GET | /reseau-local/manifest-appareil | Obtention du manifeste du serveur |
| POST | /reseau-local/appairer-appareil | Appairage d'un nouvel appareil |
| GET | /reseau-local/bootstrap-appareil | Recuperation des donnees initiales |
| POST | /reseau-local/recevoir-lot | Reception d'un lot de modifications |
| GET | /reseau-local/appareil-connecte | Page d'appairage appareil connecte |
| OPTIONS | /reseau-local/{any} | Preflight CORS |
| GET | /connected/init | Initialisation de l'appareil connecte |
| GET | /connected/sync/status | Statut de la synchronisation |
| POST | /connected/sync/run | Execution de la synchronisation |
| POST | /connected/disconnect | Deconnexion de l'appareil |
| POST | /connected/switch-user | Changement d'utilisateur connecte |

---

*Document genere le 18 juillet 2026*
*Projet Novaskol - Version 1.0.6*
*Conception et realisation d'une application de gestion scolaire hors-ligne avec Laravel, Electron et SQLite*



