<?php

namespace App\DataFixtures;

use App\Entity\Filiere;
use App\Entity\Etablissement;
use App\Entity\User;
use App\Entity\Evenement;
use App\Entity\ForumCategorie;
use App\Entity\ForumSujet;
use App\Entity\ForumMessage;
use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Entity\QuizReponse;
use App\Entity\Mentorat;
use App\Entity\Disponibilite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ========== 1. FILIERES ==========
        $filieres = [];
        $filiereData = [
            ['Genie Logiciel', 'Developpement de logiciels, conception, programmation avancee, gestion de projets informatiques. Debouches : Developpeur, Architecte logiciel, Chef de projet IT.', 'genie-logiciel.png'],
            ['Reseaux et Systemes', 'Administration des reseaux, securite informatique, virtualisation, cloud computing. Debouches : Administrateur reseau, Cyber security analyst, Cloud architect.', 'reseaux-systemes.png'],
            ['Intelligence Artificielle', 'Machine learning, deep learning, traitement du langage naturel, data science. Debouches : Data scientist, AI engineer, ML engineer.', 'ia.png'],
            ['Cybersecurite', 'Securite des systemes, cryptographie, audit de securite, penetration testing. Debouches : Pentester, Analyste SOC, RSSI.', 'cyber.png'],
            ['Data Science', 'Analyse de donnees, big data, visualisation, statistiques avancees. Debouches : Data analyst, Data engineer, Business analyst.', 'data-science.png'],
        ];

        foreach ($filiereData as $data) {
            $filiere = new Filiere();
            $filiere->setNom($data[0]);
            $filiere->setDescription($data[1]);
            $filiere->setImage($data[2]);
            $manager->persist($filiere);
            $filieres[] = $filiere;
        }

        // ========== 2. ETABLISSEMENTS ==========
        $etablissementData = [
            ['Universite de Lome', 'Boulevard du 13 Janvier, Lome, Togo', 'contact@univ-lome.tg', 'univ-lome.png'],
            ['Universite de Kara', 'Kara, Togo', 'rectorat@univ-kara.tg', 'univ-kara.png'],
            ['ISDI', '21 BKK, rue Dvaveme, derriere FUCEC Atikoume, Togo', 'isditogo@gmail.com', 'isdi.png'],
            ['IPNET Institute of Technology', 'Avenue Sarakawa, Lome, Togo', 'info@ipnet.tg', 'ipnet.png'],
            ['IAI Togo', '46G6+54 Lome, Togo', 'iaitogo@iai-togo.tg', 'iai.png'],
            ['Ecole 241', 'Boulevard de la Kara, Lome, Togo', 'contact@ecole241.tg', 'ecole241.png'],
        ];

        foreach ($etablissementData as $data) {
            $etablissement = new Etablissement();
            $etablissement->setNom($data[0]);
            $etablissement->setAdresse($data[1]);
            $etablissement->setEmail($data[2]);
            $etablissement->setImage($data[3]);
            $manager->persist($etablissement);
        }

        // ========== 3. UTILISATEURS ==========
        $userData = [
            ['ADJA', 'Kokou', 'kokou.adja@schoolprepar.com', ['ROLE_ADMIN'], null, null, null, null],
            ['ALI', 'Romaric', 'romaric.ali@ipnet.tg', ['ROLE_ELEVE'], $filieres[0], null, null, null],
            ['DJANGBEDJA', 'Essotina', 'essotina.djangbedja@univ-lome.tg', ['ROLE_MENTOR'], $filieres[1], 'Reseaux et Securite', 'Expert en reseaux avec 8 ans d\'experience chez Orange Togo. Specialiste en securite informatique et cloud.', 8],
            ['KOMLAN', 'Ayao', 'ayao.komlan@schoolprepar.com', ['ROLE_ELEVE'], $filieres[2], null, null, null],
            ['SOGLO', 'Nafissatou', 'nafissatou.soglo@ipnet.tg', ['ROLE_MENTOR'], $filieres[3], 'Data Science et IA', 'Data scientist chez Ecobank. Passionnee par l\'intelligence artificielle et le machine learning. 5 ans d\'experience.', 5],
            ['AMOUZOU', 'Koffi', 'koffi.amouzou@schoolprepar.com', ['ROLE_MENTOR'], $filieres[4], 'Developpement Web', 'Developpeur full stack senior chez WebTech. Expert Symfony, React et Node.js. Formateur certifie.', 6],
        ];

        $users = [];
        foreach ($userData as $data) {
            $user = new User();
            $user->setNom($data[0]);
            $user->setPrenom($data[1]);
            $user->setEmail($data[2]);
            $user->setRole($data[3]);
            $user->setFiliere($data[4]);
            $user->setSpecialite($data[5]);
            $user->setBiographie($data[6]);
            $user->setExperience($data[7]);
            
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'Romaric123');
            $user->setPassword($hashedPassword);
            
            $manager->persist($user);
            $users[] = $user;
        }

        // ========== 4. EVENEMENTS (9 evenements varies) ==========
        $evenementData = [
            ['Webinaire : Introduction a Symfony 7', new \DateTime('2025-05-25 14:00:00'), 'En ligne (Zoom)', 'webinaire', 'Decouvrez les nouveautes de Symfony 7 et comment creer une application moderne.', 'https://zoom.us/webinaire-symfony', 90, $filieres[0]],
            ['Atelier React pour debutants', new \DateTime('2025-05-28 10:00:00'), 'IPNET Lome', 'formation', 'Atelier pratique sur React.js. Apprenez a creer votre premiere application.', null, 180, $filieres[0]],
            ['Conference sur la CyberSecurite', new \DateTime('2025-06-05 09:00:00'), 'Universite de Lome', 'conference', 'Les enjeux de la securite informatique en Afrique. Avec des experts de la CNIL.', null, 240, $filieres[3]],
            ['Portes Ouvertes IPNET 2025', new \DateTime('2025-06-10 09:00:00'), 'IPNET Lome', 'porte_ouverte', 'Decouvrez nos formations et rencontrez nos etudiants et professeurs.', null, 360, null],
            ['Hackathon SchoolPrepar', new \DateTime('2025-06-15 08:00:00'), 'Universite de Lome', 'conference', 'Competition de programmation de 48h. Themes : IA et Developpement Web.', null, 2880, $filieres[2]],
            ['Masterclass Docker et Kubernetes', new \DateTime('2025-06-20 14:00:00'), 'En ligne (Teams)', 'webinaire', 'Apprenez a containeriser vos applications avec Docker.', 'https://teams.microsoft.com/docker', 120, $filieres[1]],
            ['Forum des Metiers Tech', new \DateTime('2025-07-01 09:00:00'), 'Palais des Congres de Lome', 'conference', 'Rencontrez des entreprises tech et decouvrez les metiers d\'avenir.', null, 480, null],
            ['Atelier Data Science avec Python', new \DateTime('2025-07-10 10:00:00'), 'IAI Togo', 'formation', 'Initiation a l\'analyse de donnees avec Pandas et Matplotlib.', null, 180, $filieres[4]],
            ['Webinaire : Carriere dans l\'IA', new \DateTime('2025-07-15 15:00:00'), 'En ligne', 'webinaire', 'Comment devenir expert en intelligence artificielle ? Par des experts de Google.', 'https://zoom.us/ia-career', 60, $filieres[2]],
        ];

        $evenements = [];
        foreach ($evenementData as $data) {
            $evenement = new Evenement();
            $evenement->setTitre($data[0]);
            $evenement->setDate($data[1]);
            $evenement->setLieu($data[2]);
            $evenement->setCategorie($data[3]);
            $evenement->setDescription($data[4]);
            $evenement->setReplayUrl($data[5]);
            $evenement->setDuree($data[6]);
            $evenement->setFiliere($data[7]);
            $manager->persist($evenement);
            $evenements[] = $evenement;
        }

        // ========== 5. PARTICIPATIONS ==========
        $users[1]->addEvenement($evenements[0]); // Romaric -> Symfony
        $users[1]->addEvenement($evenements[1]); // Romaric -> React
        $users[2]->addEvenement($evenements[2]); // Essotina -> Cyber
        $users[2]->addEvenement($evenements[0]); // Essotina -> Symfony
        $users[3]->addEvenement($evenements[4]); // Ayao -> Hackathon
        $users[4]->addEvenement($evenements[7]); // Nafissatou -> Data Science
        $users[4]->addEvenement($events[5] ?? $evenements[5]); // Nafissatou -> Docker
        $users[5]->addEvenement($evenements[8]); // Koffi -> IA Career

        // ========== 6. FORUM CATEGORIES ==========
        $forumCategories = [];
        $categorieData = [
            ['Annonces generales', 'Annonces importantes de la plateforme', 1],
            ['Orientation scolaire', 'Discussions sur les filieres et debouches', 2],
            ['Methodologie', 'Conseils pour reussir ses etudes', 3],
            ['Discussions libres', 'Sujets generaux et entraide', 4],
            ['Stages et emplois', 'Offres de stages et opportunites', 5],
            ['Developpement Web', 'Questions sur HTML, CSS, JavaScript, PHP, Symfony', 6],
            ['Data & IA', 'Discussions sur l\'IA, le Machine Learning et la Data Science', 7],
        ];

        foreach ($categorieData as $data) {
            $categorie = new ForumCategorie();
            $categorie->setNom($data[0]);
            $categorie->setDescription($data[1]);
            $categorie->setOrdre($data[2]);
            $manager->persist($categorie);
            $forumCategories[] = $categorie;
        }

        // ========== 7. FORUM SUJETS ==========
        $sujets = [];
        $sujetData = [
            ['Bienvenue sur SchoolPrepar !', 'Bienvenue a tous sur notre plateforme d\'orientation scolaire.', $users[0], $forumCategories[0]],
            ['Quelle filiere choisir entre Genie Logiciel et IA ?', 'Je suis perdu, des conseils ?', $users[1], $forumCategories[1]],
            ['Comment reussir ses examens en informatique ?', 'Partagez vos techniques de revision.', $users[2], $forumCategories[2]],
            ['Stage de fin d\'etudes', 'Ou trouver un stage en developpement web ?', $users[3], $forumCategories[4]],
            ['Symfony vs Laravel', 'Quel framework choisir pour mes projets ?', $users[4], $forumCategories[5]],
            ['Comment debuter en IA ?', 'Quelles ressources recommandez-vous ?', $users[5], $forumCategories[6]],
        ];

        foreach ($sujetData as $data) {
            $sujet = new ForumSujet();
            $sujet->setTitre($data[0]);
            $sujet->setContenu($data[1]);
            $sujet->setAuteur($data[2]);
            $sujet->setCategorie($data[3]);
            $sujet->setDateCreation(new \DateTime());
            $manager->persist($sujet);
            $sujets[] = $sujet;
        }

        // ========== 8. FORUM MESSAGES ==========
        $messageData = [
            ['Merci pour cet accueil !', $users[1], $sujets[0]],
            ['Je recommande Genie Logiciel, beaucoup de debouches !', $users[2], $sujets[1]],
            ['La methode Pomodoro m\'aide beaucoup.', $users[0], $sujets[2]],
            ['Regarde sur LinkedIn et WeAreTech.', $users[4], $sujets[3]],
            ['Symfony est plus complet pour les grandes apps.', $users[5], $sujets[4]],
            ['Commence par Python et TensorFlow.', $users[2], $sujets[5]],
        ];

        foreach ($messageData as $data) {
            $message = new ForumMessage();
            $message->setContenu($data[0]);
            $message->setAuteur($data[1]);
            $message->setSujet($data[2]);
            $message->setDateCreation(new \DateTime());
            $manager->persist($message);
        }

        // ========== 9. QUIZ INFORMATIQUES REALISTES ==========
        
        // Quiz 1: Developpement Web
        $quiz1 = new Quiz();
        $quiz1->setTitre('Quiz Developpement Web');
        $quiz1->setDescription('Testez vos connaissances en HTML, CSS, JavaScript et frameworks.');
        $quiz1->setType('connaissance');
        $quiz1->setDuree(15);
        $quiz1->setEstActif(true);
        $manager->persist($quiz1);
        
        $questionsWeb = [
            ['Que signifie PHP ?', [
                ['texte' => 'Personal Home Page', 'correcte' => false, 'points' => 0],
                ['texte' => 'Pre Hypertext Processor', 'correcte' => false, 'points' => 0],
                ['texte' => 'Hypertext Preprocessor', 'correcte' => true, 'points' => 20],
                ['texte' => 'Professional Hosting Platform', 'correcte' => false, 'points' => 0],
            ]],
            ['Quelle est la bonne syntaxe pour une fonction en JavaScript ?', [
                ['texte' => 'function:maFonction()', 'correcte' => false, 'points' => 0],
                ['texte' => 'function = myFunction()', 'correcte' => false, 'points' => 0],
                ['texte' => 'function myFunction()', 'correcte' => true, 'points' => 20],
                ['texte' => 'def myFunction()', 'correcte' => false, 'points' => 0],
            ]],
            ['Que permet CSS Grid ?', [
                ['texte' => 'Cree des animations', 'correcte' => false, 'points' => 0],
                ['texte' => 'Cree des mises en page en 2D', 'correcte' => true, 'points' => 20],
                ['texte' => 'Gerer des bases de donnees', 'correcte' => false, 'points' => 0],
                ['texte' => 'Coder la logique metier', 'correcte' => false, 'points' => 0],
            ]],
            ['Quelle commande cree un nouveau projet Symfony ?', [
                ['texte' => 'symfony new projet', 'correcte' => true, 'points' => 20],
                ['texte' => 'create-symfony projet', 'correcte' => false, 'points' => 0],
                ['texte' => 'symfony create projet', 'correcte' => false, 'points' => 0],
                ['texte' => 'new symfony projet', 'correcte' => false, 'points' => 0],
            ]],
            ['Que signifie API ?', [
                ['texte' => 'Application Programming Interface', 'correcte' => true, 'points' => 20],
                ['texte' => 'Application Process Integration', 'correcte' => false, 'points' => 0],
                ['texte' => 'Automated Program Interface', 'correcte' => false, 'points' => 0],
                ['texte' => 'Advanced Programming Interface', 'correcte' => false, 'points' => 0],
            ]],
        ];
        
        foreach ($questionsWeb as $qData) {
            $question = new QuizQuestion();
            $question->setQuiz($quiz1);
            $question->setTexte($qData[0]);
            $question->setOrdre(0);
            $manager->persist($question);
            foreach ($qData[1] as $rData) {
                $reponse = new QuizReponse();
                $reponse->setQuestion($question);
                $reponse->setTexte($rData['texte']);
                $reponse->setEstCorrecte($rData['correcte']);
                $reponse->setPoints($rData['points']);
                $manager->persist($reponse);
            }
        }
        
        // Quiz 2: Bases de donnees
        $quiz2 = new Quiz();
        $quiz2->setTitre('Quiz Bases de Donnees');
        $quiz2->setDescription('Testez vos connaissances en SQL et gestion de bases de donnees.');
        $quiz2->setType('connaissance');
        $quiz2->setDuree(12);
        $quiz2->setEstActif(true);
        $manager->persist($quiz2);
        
        $questionsSQL = [
            ['Que signifie SQL ?', [
                ['texte' => 'Structured Query Language', 'correcte' => true, 'points' => 20],
                ['texte' => 'Simple Query Language', 'correcte' => false, 'points' => 0],
                ['texte' => 'Standard Query Language', 'correcte' => false, 'points' => 0],
                ['texte' => 'System Query Language', 'correcte' => false, 'points' => 0],
            ]],
            ['Quelle commande recupere toutes les donnees d\'une table ?', [
                ['texte' => 'GET * FROM table', 'correcte' => false, 'points' => 0],
                ['texte' => 'SELECT * FROM table', 'correcte' => true, 'points' => 20],
                ['texte' => 'EXTRACT * FROM table', 'correcte' => false, 'points' => 0],
                ['texte' => 'SHOW * FROM table', 'correcte' => false, 'points' => 0],
            ]],
            ['Qu\'est-ce qu\'une cl\'e primaire ?', [
                ['texte' => 'Une colonne qui peut etre nulle', 'correcte' => false, 'points' => 0],
                ['texte' => 'Une colonne qui identifie de maniere unique chaque ligne', 'correcte' => true, 'points' => 20],
                ['texte' => 'Une colonne qui contient des images', 'correcte' => false, 'points' => 0],
                ['texte' => 'Une colonne qui lie deux tables', 'correcte' => false, 'points' => 0],
            ]],
            ['Que fait la commande JOIN ?', [
                ['texte' => 'Supprime des donnees', 'correcte' => false, 'points' => 0],
                ['texte' => 'Cree une nouvelle table', 'correcte' => false, 'points' => 0],
                ['texte' => 'Combine des lignes de deux tables', 'correcte' => true, 'points' => 20],
                ['texte' => 'Renomme une colonne', 'correcte' => false, 'points' => 0],
            ]],
            ['Quelle commande supprime une table ?', [
                ['texte' => 'DELETE TABLE', 'correcte' => false, 'points' => 0],
                ['texte' => 'REMOVE TABLE', 'correcte' => false, 'points' => 0],
                ['texte' => 'DROP TABLE', 'correcte' => true, 'points' => 20],
                ['texte' => 'TRUNCATE TABLE', 'correcte' => false, 'points' => 0],
            ]],
        ];
        
        foreach ($questionsSQL as $qData) {
            $question = new QuizQuestion();
            $question->setQuiz($quiz2);
            $question->setTexte($qData[0]);
            $question->setOrdre(0);
            $manager->persist($question);
            foreach ($qData[1] as $rData) {
                $reponse = new QuizReponse();
                $reponse->setQuestion($question);
                $reponse->setTexte($rData['texte']);
                $reponse->setEstCorrecte($rData['correcte']);
                $reponse->setPoints($rData['points']);
                $manager->persist($reponse);
            }
        }
        
        // Quiz 3: Algorithmique
        $quiz3 = new Quiz();
        $quiz3->setTitre('Quiz Algorithmique');
        $quiz3->setDescription('Testez votre logique de programmation.');
        $quiz3->setType('connaissance');
        $quiz3->setDuree(10);
        $quiz3->setEstActif(true);
        $manager->persist($quiz3);
        
        $questionsAlgo = [
            ['Qu\'est-ce qu\'un algorithme ?', [
                ['texte' => 'Une suite d\'instructions pour resoudre un probleme', 'correcte' => true, 'points' => 20],
                ['texte' => 'Un language de programmation', 'correcte' => false, 'points' => 0],
                ['texte' => 'Une base de donnees', 'correcte' => false, 'points' => 0],
                ['texte' => 'Un compilateur', 'correcte' => false, 'points' => 0],
            ]],
            ['Que signifie la complexite O(n) ?', [
                ['texte' => 'Temps constant', 'correcte' => false, 'points' => 0],
                ['texte' => 'Temps lineaire', 'correcte' => true, 'points' => 20],
                ['texte' => 'Temps quadratique', 'correcte' => false, 'points' => 0],
                ['texte' => 'Temps logarithmique', 'correcte' => false, 'points' => 0],
            ]],
            ['Qu\'est-ce qu\'une pile (stack) ?', [
                ['texte' => 'Structure FIFO (First In First Out)', 'correcte' => false, 'points' => 0],
                ['texte' => 'Structure LIFO (Last In First Out)', 'correcte' => true, 'points' => 20],
                ['texte' => 'Structure arborescente', 'correcte' => false, 'points' => 0],
                ['texte' => 'Structure chaotique', 'correcte' => false, 'points' => 0],
            ]],
            ['Quel algorithme de tri est le plus rapide dans le pire cas ?', [
                ['texte' => 'Tri a bulles', 'correcte' => false, 'points' => 0],
                ['texte' => 'Tri par insertion', 'correcte' => false, 'points' => 0],
                ['texte' => 'Tri fusion (Merge sort)', 'correcte' => true, 'points' => 20],
                ['texte' => 'Tri selection', 'correcte' => false, 'points' => 0],
            ]],
            ['Que fait une fonction recursive ?', [
                ['texte' => 'Elle s\'appelle elle-meme', 'correcte' => true, 'points' => 20],
                ['texte' => 'Elle appelle une autre fonction', 'correcte' => false, 'points' => 0],
                ['texte' => 'Elle renvoie toujours 0', 'correcte' => false, 'points' => 0],
                ['texte' => 'Elle cre une boucle infinie', 'correcte' => false, 'points' => 0],
            ]],
        ];
        
        foreach ($questionsAlgo as $qData) {
            $question = new QuizQuestion();
            $question->setQuiz($quiz3);
            $question->setTexte($qData[0]);
            $question->setOrdre(0);
            $manager->persist($question);
            foreach ($qData[1] as $rData) {
                $reponse = new QuizReponse();
                $reponse->setQuestion($question);
                $reponse->setTexte($rData['texte']);
                $reponse->setEstCorrecte($rData['correcte']);
                $reponse->setPoints($rData['points']);
                $manager->persist($reponse);
            }
        }

        // ========== 10. MENTORAT (demandes supplementaires) ==========
        $mentoratData = [
            [$users[1], $users[2], 'accepte', 'Je souhaite etre accompagne en reseaux et systemes'],
            [$users[1], $users[4], 'en_attente', 'Aide pour l\'audit et le controle'],
            [$users[3], $users[5], 'accepte', 'Besoin de conseils pour les cours de developpement'],
            [$users[3], $users[4], 'refuse', 'Merci mais pas besoin pour l\'instant'],
            [$users[5], $users[2], 'en_attente', 'Formation en cybersecurite'],
        ];

        foreach ($mentoratData as $data) {
            $mentorat = new Mentorat();
            $mentorat->setEleve($data[0]);
            $mentorat->setMentor($data[1]);
            $mentorat->setStatut($data[2]);
            $mentorat->setMessage($data[3]);
            $mentorat->setDemandeLe(new \DateTime());
            if ($data[2] != 'en_attente') {
                $mentorat->setReponseLe(new \DateTime());
            }
            $manager->persist($mentorat);
        }

        // ========== 11. DISPONIBILITES MENTORS ==========
        $dispoData = [
            [$users[2], 1, '09:00', '12:00', true],
            [$users[2], 3, '14:00', '17:00', true],
            [$users[4], 2, '10:00', '13:00', true],
            [$users[4], 4, '15:00', '18:00', true],
            [$users[5], 1, '08:00', '12:00', true],
            [$users[5], 5, '14:00', '18:00', true],
        ];

        foreach ($dispoData as $data) {
            $disponibilite = new Disponibilite();
            $disponibilite->setMentor($data[0]);
            $disponibilite->setJourSemaine($data[1]);
            $disponibilite->setHeureDebut(new \DateTime($data[2]));
            $disponibilite->setHeureFin(new \DateTime($data[3]));
            $disponibilite->setEstDisponible($data[4]);
            $manager->persist($disponibilite);
        }

        $manager->flush();
    }
}