markdown

# SchoolPrepar

Plateforme d'orientation scolaire et professionnelle développée avec Symfony.

---

## Architecture Technique

| Composant | Technologie |
|-----------|-------------|
| Framework | Symfony 7.2 |
| Moteur de templates | Twig |
| ORM | Doctrine |
| Base de données | PostgreSQL / MySQL |
| CSS | Bootstrap 4 + Custom |
| JavaScript | AJAX, jQuery |
| Gestion de versions | Git |

---

## Fonctionnalités Implémentées

### Front-office (Utilisateurs)

| Module | Fonctionnalités |
|--------|-----------------|
| Authentification | Inscription (choix role Eleve/Mentor), Connexion, Deconnexion |
| Filières | Liste des filieres, Popup detail, Load more |
| Etablissements | Liste des etablissements, Popup detail, Load more |
| Evenements | Liste filtree (categorie/filiere), Inscription, Pagination |
| Forum | Categories, Creation de sujets, Reponses, Epingles |
| Quiz | Passer les quiz, Calcul score, Resultats detailles, Historique |
| Mentorat | Liste des mentors, Demande de mentorat, Acceptance/Refus |
| Messagerie | Conversations privees, Envoi messages en AJAX |
| Profil | Modification infos, Changement mot de passe, Profil mentor |

### Back-office (Administration)

| Module | Fonctionnalites |
|--------|-----------------|
| Dashboard | Statistiques (filieres, etablissements, utilisateurs, evenements, quiz, forum, mentorat) |
| Filieres | CRUD complet, Upload image, Pagination |
| Etablissements | CRUD complet, Upload image, Pagination |
| Evenements | CRUD complet, Categorie, Filiere associee, Pagination |
| Utilisateurs | CRUD complet, Gestion roles (Eleve/Mentor/Admin), Pagination |
| Forum | Gestion des categories |
| Quiz | CRUD complet, Gestion questions/reponses, Pagination |
| Mentorat | Gestion des demandes, Acceptance/Refus, Pagination |

---

## Installation et Lancement

### Prérequis

- PHP 8.2 ou superieur
- Composer
- PostgreSQL ou MySQL
- Symfony CLI (recommandé)

### Etapes d'installation

**1. Cloner le projet**

```bash
git clone https://github.com/Edmund1er/symfony_schoolprepar_project_roms.git
cd symfony_schoolprepar_project_roms

2. Installer les dependances
bash

composer install

3. Configurer la base de donnees

Copier le fichier .env en .env.local et modifier la variable DATABASE_URL :
env

# PostgreSQL
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/schoolprepar_db?serverVersion=15&charset=utf8"

# MySQL
DATABASE_URL="mysql://root:password@127.0.0.1:3306/schoolprepar_db?serverVersion=8.0.32&charset=utf8mb4"

4. Creer et migrer la base de donnees
bash

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

5. Charger les donnees de demonstration (optionnel)
bash

php bin/console doctrine:fixtures:load

6. Lancer le serveur
bash

symfony server:start

Acceder à : http://localhost:8000
Structure des controleurs
Front-office
Controleur	Role
HomeController	Page d'accueil
FiliereController	Liste et detail des filieres
EtablissementController	Liste et detail des etablissements
EvenementController	Liste, filtres et inscription aux evenements
ForumController	Forum (categories, sujets, messages)
QuizController	Quiz, resultats, historique
MentoratController	Mentorat (demandes, acceptance, refus)
MessageController	Messagerie privee (conversations)
ProfilController	Gestion du profil utilisateur
SecurityController	Authentification (connexion, inscription, deconnexion)
Back-office
Controleur	Role
AdminDashboardController	Tableau de bord
AdminFiliereController	CRUD filieres
AdminEtablissementController	CRUD etablissements
AdminEvenementController	CRUD evenements
AdminUtilisateurController	CRUD utilisateurs
AdminForumCategorieController	CRUD categories forum
AdminQuizController	CRUD quiz
AdminQuizQuestionController	CRUD questions/reponses
AdminMentoratController	Gestion demandes mentorat
Modele de donnees
Entites principales
Entite	Role	Relations
User	Utilisateurs (eleves, mentors, admins)	N:1 Filiere, N:N Evenement, 1:N Mentorat, 1:N ForumSujet, 1:N ForumMessage, 1:N Conversation, 1:N Message
Filiere	Filieres de formation	1:N User
Etablissement	Etablissements scolaires	-
Evenement	Evenements (webinaires, portes ouvertes)	N:N User, N:1 Filiere
ForumCategorie	Categories du forum	1:N ForumSujet
ForumSujet	Sujets du forum	1:N ForumMessage
ForumMessage	Messages du forum	-
Quiz	Quiz d'orientation	1:N QuizQuestion
QuizQuestion	Questions de quiz	1:N QuizReponse
QuizReponse	Reponses possibles	-
Mentorat	Demandes de mentorat	-
Conversation	Conversations privees	1:N Message
Message	Messages prives	-
Relations implementees
Type	Entites	Table de liaison
N:N	User ↔ Evenement	user_evenement
1:N	Filiere → User	user.filiere_id
1:N	ForumCategorie → ForumSujet	forum_sujet.categorie_id
1:N	ForumSujet → ForumMessage	forum_message.sujet_id
1:N	Quiz → QuizQuestion	quiz_question.quiz_id
1:N	QuizQuestion → QuizReponse	quiz_reponse.question_id
Structure des templates
text

templates/
├── admin/                      # Back-office
│   ├── base.html.twig
│   ├── dashboard.html.twig
│   ├── filiere/
│   ├── etablissement/
│   ├── evenement/
│   ├── utilisateur/
│   ├── forum_categorie/
│   ├── quiz/
│   ├── quiz_question/
│   └── mentorat/
├── front/                      # Front-office
│   ├── base.html.twig
│   ├── home.html.twig
│   ├── filiere/
│   ├── etablissement/
│   ├── evenement/
│   ├── forum/
│   ├── mentorat/
│   ├── message/
│   ├── profil/
│   ├── quiz/
│   └── security/
└── partials/                   # Composants reutilisables
    ├── nav.html.twig
    └── footer.html.twig

Identifiants de demonstration
Role	Email	Mot de passe
Administrateur	kokou.adja@schoolprepar.com	Romaric123
Eleve	romaric.ali@ipnet.tg	Romaric123
Mentor	essotina.djangbedja@univ-lome.tg	Romaric123
Routes principales
Front-office
Route	Description
/	Page d'accueil
/filiere	Liste des filieres
/etablissement	Liste des etablissements
/evenement	Liste des evenements
/forum	Forum communautaire
/quiz	Quiz d'orientation
/mentorat	Liste des mentors
/message	Messagerie privee
/profil	Mon profil
/connexion	Connexion
/inscription	Inscription
Back-office
Route	Description
/admin	Dashboard
/admin/filiere	Gestion des filieres
/admin/etablissement	Gestion des etablissements
/admin/evenement	Gestion des evenements
/admin/utilisateur	Gestion des utilisateurs
/admin/forum/categorie	Gestion des categories forum
/admin/quiz	Gestion des quiz
/admin/mentorat	Gestion du mentorat
Auteur
Champ	Information
Nom	ALI Pouwedeou Romaric
Filiere	GL (Genie Logiciel) - Licence 2
Semestre	4
Institution	IPNet Institute of Technology
Annee academique	2025-2026
UE	IT 232 - Developpement Web II
Encadrant	M. EDOU Dodji
Depot GitHub

https://github.com/Edmund1er/symfony_schoolprepar_project_roms.git
