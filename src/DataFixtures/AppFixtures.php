<?php

namespace App\DataFixtures;

use App\Entity\Filiere;
use App\Entity\Etablissement;
use App\Entity\User;
use App\Entity\Evenement;
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
            ['Génie Logiciel', 'Développement de logiciels, conception, programmation avancée, gestion de projets informatiques'],
            ['Réseaux et Systèmes', 'Administration des réseaux, sécurité informatique, virtualisation, cloud computing'],
            ['Comptabilité', 'Comptabilité générale, comptabilité analytique, audit financier, fiscalité'],
            ['Audit et Contrôle', 'Audit interne, contrôle de gestion, gestion des risques, conformité'],
            ['Sciences Biologiques', 'Biologie animale, biologie végétale, génétique, écologie, physiologie'],
        ];

        foreach ($filiereData as $data) {
            $filiere = new Filiere();
            $filiere->setNom($data[0]);
            $filiere->setDescription($data[1]);
            $manager->persist($filiere);
            $filieres[] = $filiere;
        }

        // ========== 2. ETABLISSEMENTS ==========
        $etablissementData = [
            ['Université de Lomé', 'Boulevard du 13 Janvier, Lomé, Togo', 'contact@univ-lome.tg'],
            ['Université de Kara', 'Kara, Togo', 'rectorat@univ-kara.tg'],
            ['Université de Dapaong', 'Dapaong, Savanes, Togo', 'contact@univ-dapaong.tg'],
            ['IPNET Institute of Technology', 'Avenue Sarakawa, Lomé, Togo', 'info@ipnet.tg'],
            ['ESTBA', 'Lomé, Togo', 'info@estba.tg'],
        ];

        foreach ($etablissementData as $data) {
            $etablissement = new Etablissement();
            $etablissement->setNom($data[0]);
            $etablissement->setAdresse($data[1]);
            $etablissement->setEmail($data[2]);
            $manager->persist($etablissement);
        }

        // ========== 3. UTILISATEURS ==========
        $userData = [
            ['ADJA', 'Kokou', 'kokou.adja@schoolprepar.com', ['ROLE_ADMIN'], null],
            ['ALI', 'Romaric', 'romaric.ali@ipnet.tg', ['ROLE_ELEVE'], $filieres[0]],
            ['DJANGBEDJA', 'Essotina', 'essotina.djangbedja@univ-lome.tg', ['ROLE_MENTOR'], $filieres[1]],
            ['KOMLAN', 'Ayao', 'ayao.komlan@schoolprepar.com', ['ROLE_ELEVE'], $filieres[2]],
            ['SOGLO', 'Nafissatou', 'nafissatou.soglo@ipnet.tg', ['ROLE_MENTOR'], $filieres[3]],
        ];

        $users = [];
        foreach ($userData as $data) {
            $user = new User();
            $user->setNom($data[0]);
            $user->setPrenom($data[1]);
            $user->setEmail($data[2]);
            $user->setRole($data[3]);
            $user->setFiliere($data[4]);
            
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'Romaric123');
            $user->setPassword($hashedPassword);
            
            $manager->persist($user);
            $users[] = $user;
        }

        // ========== 4. EVENEMENTS ==========
        $evenementData = [
            ['Portes Ouvertes IPNET 2025', new \DateTime('2025-05-15 09:00:00'), 'IPNET Lomé'],
            ['Webinaire : Réussir son orientation', new \DateTime('2025-04-20 14:00:00'), 'En ligne'],
            ['Hackathon SchoolPrepar', new \DateTime('2025-06-10 08:00:00'), 'Université de Lomé'],
            ['Rencontre Mentor-Élève', new \DateTime('2025-05-05 10:00:00'), 'IPNET Lomé'],
            ['Forum des Métiers et Formations', new \DateTime('2025-07-01 09:00:00'), 'Palais des Congrès de Lomé'],
        ];

        $evenements = [];
        foreach ($evenementData as $data) {
            $evenement = new Evenement();
            $evenement->setTitre($data[0]);
            $evenement->setDate($data[1]);
            $evenement->setLieu($data[2]);
            $manager->persist($evenement);
            $evenements[] = $evenement;
        }

        // ========== 5. PARTICIPATIONS ==========
        $users[1]->addEvenement($evenements[0]);
        $users[1]->addEvenement($evenements[1]);
        $users[2]->addEvenement($evenements[0]);
        $users[3]->addEvenement($evenements[2]);
        $users[4]->addEvenement($evenements[3]);

        $manager->flush();
    }
}