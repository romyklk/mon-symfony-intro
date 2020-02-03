<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TwigController
 * @package App\Controller
 *
 * Préfixe de route : toutes les url définies dans les routes de ce controller seront
 * préfixées par twig
 * @Route("/twig")
 */
class TwigController extends AbstractController
{
    /**
     * L'url de cette route est /wig/ ou /twig parcequ'il a le préfixe de route défini
     * pour la classe
     *
     * @Route("/")
     */
    public function index()
    {

        $test = 'Ma variable de test dans le controller';
        /**
         * Pour voir mon dump il faut aller dans le navigateur dans la barre de débug
         * en bas et mettre le curseur sur le rond
         */
        dump($test);

        return $this->render('twig/index.html.twig',
            [
                'demain'=>new \DateTime('+1day')
            ]);
    }
}
