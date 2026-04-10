

Plateforme d'orientation scolaire et professionnelle développée avec Symfony.

---

## Architecture Technique

| Composant | Technologie |
|-----------|-------------|
| Framework | Symfony 7.2 |
| Moteur de templates | Twig |
| ORM | Doctrine |
| Base de données | MySQL |
| Gestion de versions | Git |

### Structure des contrôleurs

| Contrôleur | Rôle |
|------------|------|
| `MainController` | Pages publiques (accueil) |
| `SchoolController` | Gestion des établissements (front-office) |
| `Admin\AdminFiliereController` | CRUD filières (back-office) |
| `Admin\AdminEtablissementController` | CRUD établissements (back-office) |
| `Admin\AdminUtilisateurController` | CRUD utilisateurs (back-office) |
| `Admin\AdminDashboardController` | Tableau de bord administrateur |

---

## Installation et Lancement

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- MySQL
- Symfony CLI (recommandé) ou WAMP/XAMPP

### Étapes d'installation

**1. Cloner le projet**

```bash
git clone https://github.com/Edmund1er/symfony_schoolprepar_project_roms.git
cd symfony_schoolprepar_project_roms

2. Installer les dépendances

composer install

3. Configurer la base de données

Copier le fichier .env en .env.local et modifier la variable DATABASE_URL :
env

DATABASE_URL="mysql://root:password@127.0.0.1:3306/schoolprepar_db?serverVersion=8.0.32&charset=utf8mb4"

4. Créer et migrer la base de données

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

5. Lancer le serveur

Méthode A - Symfony CLI 


symfony server:start

Accéder à : http://localhost:8000

Méthode B - WAMP / XAMPP

    Déplacer le projet dans C:\wamp64\www\ ou C:\xampp\htdocs\

    Démarrer WAMP ou XAMPP

    Accéder à : http://localhost/symfony_schoolprepar_project_roms/public/

## Routes disponibles

### Front-office (public)
---
| Page | Route | Description |
|------|-------|-------------|
| Accueil | `/` ou `/accueil` | Page \'accueil dynamique |
| Liste des établissements | `/liste_etablissement` | Voir tous les établissements |

### Back-office (administration)

| Page | Route | Description |
|------|-------|-------------|
| Dashboard | `/admin` | Tableau de bord administrateur |
| Gestion des filières | `/admin/filiere` | CRUD complet des filières |
| Gestion des établissements | `/admin/etablissement` | CRUD complet des établissements |
| Gestion des utilisateurs | `/admin/utilisateur` | CRUD complet des utilisateurs |

---

## Structure des templates
templates/
├── admin/
│   ├── base.html.twig           # Layout principal admin
│   ├── dashboard.html.twig      # Tableau de bord
│   ├── filiere/                 # CRUD filières
│   │   ├── index.html.twig
│   │   ├── new.html.twig
│   │   ├── edit.html.twig
│   │   ├── show.html.twig
│   │   ├── _form.html.twig
│   │   └── _delete_form.html.twig
│   ├── etablissement/           # CRUD établissements
│   └── utilisateur/             # CRUD utilisateurs
├── front/                       # Templates front-office
└── partials/                    # Éléments réutilisables


### Système d'héritage

Toutes les pages héritent de `base.html.twig` qui contient :

- La structure HTML globale
- Les imports CSS (Bootstrap, Material Able)
- Les blocs dynamiques : `{% block title %}` et `{% block body %}`

---

## Modèle de données

### Entités principales

| Entité | Rôle | Relations |
|--------|------|-----------|
| Filiere | Filières de formation | 1:N vers User |
| Etablissement | Établissements scolaires | - |
| User | Utilisateurs (élèves, mentors, admins) | N:1 vers Filiere, N:N vers Evenement |
| Evenement | Événements (webinaires, portes ouvertes) | N:N vers User |

### Relations implémentées

| Type | Entités | Table de liaison |
|------|---------|------------------|
| 1:N | Filiere → User | user.filiere_id |
| N:N | User ↔ Evenement | user_evenement |

---

## Auteur
---
| Champ | Information |
|-------|-------------|
| Nom | ALI Pouwèdéou Romaric |
| Filière | GL (Génie Logiciel) - Licence 2 |
| Semestre | 4 |
| Institution | IPNet Institute of Technology |
| Année académique | 2025-2026 |
| UE | IT 232 - Développement Web II |
| Encadrant | M. EDOU Dodji |
---