# SchoolPrepar

##  Architecture Technique

 **Framework :** Symfony 6/7
 **Moteur de template :** Twig 
 **Contrôleurs :** 
    - `MainController` : Gestion de la page d'accueil dynamique.
    - `SchoolController` : Gestion des fiches établissements.

**Outils de versionnage qu'on va utilisé :** Git

## Installation et Lancement

Pour avoir et exécuter ce projet localement :

1.  **on doit cloner le projet:**

 dans git bash ou dans le cmd on se place dans le dossier dans le quel on veux avoir le projet 
 - ensuite on tape la commande : 
 
    `git clone https://github.com/Edmund1er/symfony_schoolprepar_project_roms.git`
    
2.  **on doit installer composer et les dependances :**

    on entre dans le dossier du projet et installez les bibliothèques nécessaires avec Composer avec cette commande :
        `composer install`

3.  **on démarre le serveur   :**
    
    * **avec le serveur Symfony**
       Lancez le serveur local de Symfony : `symfony server:start`

        Accédez ensuite à l'adresse : `http://localhost:8000`
     pour voir la page d'accueil il faut aller a l'afresse : `http://127.0.0.1:8000/accueil`

    * **avec WAMP / XAMPP**

        1. Déplacez le dossier du projet dans `C:\wamp64\www` ou `C:\xampp\htdocs`.
        2. Lancez WAMP ou XAMPP.
        3. Accédez à l'adresse : `http://localhost/schoolprepar/public/`
  
    
    Le dossier /public/ est obligatoire car c'est là que se trouve le point d'entrée de Symfony

##  les pages

Une fois qu'on a lancé le serveur, on pouvez teste les routes suivantes :
* **Accueil Dynamique :** `/accueil` 
* **Liste des Établissements :** `/liste_etablissement` 

## 5. Auteurs
* **Nom & Prénoms :** ALI Pouwèdéou
* **Filière :** GL Licence 2 - Semestre 4
* **Institution :** IPNet Institute of Technology
* **Année Académique :** 2025-2026


## 📂 Navigation et Structure des Templates

Le projet utilise un système d'héritage de templates pour garantir une interface cohérente.

### 🗺️ Détail des Routes
| Page | Route | Contrôleur | Template Twig |
| :--- | :--- | :--- | :--- |
| **Accueil** | `/accueil` | `MainController` | `templates/main/index.html.twig` |
| **Liste Établissements** | `/liste_etablissement` | `SchoolController` | `templates/school/index.html.twig` |
| **Admin : Dashboard** | `/admin/etablissement` | `Admin/EtablissementController` | `templates/admin/etablissement/index.html.twig` |

### 🏗️ Organisation des Templates 

Toutes les pages de l'administration héritent d'une structure commune pour maintenir le design **Material Able** :

1. **`base.html.twig`** : Contient la structure HTML globale, les imports CSS (dont notre `style.css` modifié) et les scripts JS.
2. **Blocs dynamiques** :
    * `{% block title %}` : Définit le titre de l'onglet dynamiquement.
    * `{% block body %}` : Injecte le contenu spécifique de chaque page (tableaux, formulaires).

### 🎨 Personnalisations CSS (Design Fixes)
Le fichier `public/assets/admin/css/style.css` a été optimisé pour corriger les défauts du template original :
* **Sidebar (Barre bleue) :** Largeur augmentée à **280px** pour éviter que les titres longs comme *"Gestion des établissements"* ne débordent.
* **Scroll Dynamique :** Ajout d'un overflow vertical (`scroll`) sur le menu latéral pour permettre l'accès à tous les modules, même sur petit écran.
* **Mise en page :** Ajustement des marges du contenu principal (`pcoded-content`) pour s'aligner sur la nouvelle largeur du menu.