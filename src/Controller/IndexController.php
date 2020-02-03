<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * la page à la racine du nom de domaine
     * @Route("/")
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'Mon premier controller symfony',
        ]);
    }

    /**
     * localhost:8000/hello
     * URL de la page
     * @Route("hello")
     */
    public function hello()
    {
        // Rendu du fichier qui construit le html contenu dans la page
        // Chemin à partir de la racine du repertoire templates
        return $this->render('index/hello.html.twig');
    }

    /**
     * une route avec une partie variable (entre accolade) le $qui en paramètre de ma méthose
     * (même nom que dans la route) contient la valeur de cette partie variable
     *
     * @Route("/bonjour/{qui}")
     */
    public function bonjour($qui)
    {
        echo $qui;

        return$this->render(
            'index/bonjour.html.twig',
            [
                'nom'=> $qui
            ]
        );
    }

    /**
     * La partie variable de la route optionnelle (avec une valeur par défaut):la route
     * matche /salut/unNom : $qui vaut 'unNom'
     * et matche aussi  /salut et /salu/ : $qui vaut 't'as pas mis ton nom'
     *
     * @Route("salut/{qui}",defaults={"qui": "t'as pas mis ton nom"})
     */
    public function salut($qui)
    {
        return $this->render(
            'index/salut.html.twig',
                [
                  'qui'=>$qui
                ]
        );
    }

    /**
     * @Route("/categorie/{id}", requirements={"id": "\d+"})
     * \d+ veut dire digit(chiffre) et + veut dire 1 ou plusieurs donc
     * le id devra être obligatoirement un chiffre
     *
     */
    public function categorie($id)
    {
        return $this->render(
            'index/categorie.html.twig',
            [
                'id'=>$id
            ]
        );
    }
}
