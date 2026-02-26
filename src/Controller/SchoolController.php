<?php 
namespace App\Controller; 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; 


class SchoolController extends AbstractController
{ 
    public function liste() : Response
    { 
        $ecoles = ["IPNet", "UL", "EPL", "IAI", "ESGIS"]; 

        return $this->render('liste.html.twig', ['listes_ecoles' => $ecoles]);
    } 
}