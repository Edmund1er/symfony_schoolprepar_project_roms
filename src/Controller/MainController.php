<?php 

namespace App\Controller; 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; 


final class MainController   extends AbstractController
    { 
        public function index() : Response
            { 
                $nom = "ALI Pouwèdéou"; 
                 $ecoles = ["IPNet ", "Université de Lomé ", "EPL","Institut Africain d'Informatique IAI","ESGIS"]; 

                return $this->render('Accueil.html.twig', ['liste_ecoles' => $ecoles,'etudiant'=> $nom]);
   

            } 
    }



?>


