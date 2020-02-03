<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HttpController
 * @package App\Controller
 * @Route("/http")
 */
class HttpController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('http/index.html.twig', [
            'controller_name' => 'HttpController',
        ]);
    }

    /**
     * @Route("/request")
     */
    public function request(Request $request)
    {
        //http://localhost:8000/request?nom=KOULEKO&prenom=romuald
        dump($_GET);//['nom'=>'KOULEKO', 'prenom'=>'romuald']

        // $request->query est une surcouche en objet au tableau $_GET
        dump($request->query->all()); //['nom'=>'KOULEKO', 'prenom'=>'romuald']

        // $_GET['nom']
        dump($request->query->get('nom')); // KOULEKO

        dump($request->query->get('surnom')); // null


        // optionnel : valeur par défaut si vide
        dump($request->query->get('surnom','John DOE')); // John DOE

        // isset($_GET[['surnom'])
        dump($request->query->has('surnom')); // false



        dump($request->getMethod());

        if ($request->isMethod('POST')){ //$request->getMethod() =='POST'
            //$request->request est une surcouche en objet au tableau $_POST
         dump($request->request->all());
        }


      return $this->render('http/request.html.twig');
    }

    //public function session(Request $request)
   // {
    //    // Pour accéder à la session depuis l'objet Request
  //      $session = $request->getSession();
   // }

    // OU

    /**
     * @param SessionInterface $session
     *
     * @Route("/session")
     */
    public function session(SessionInterface $session)
    {
        // ajouter des éléments à la session
        $session->set('nom','ANEST');
        $session->set('prenom','Julien');

        // Les éléments stockés par l'objet session le
        // sont dans $_SESSION['_sf2_attributes']
        dump($_SESSION);

        //Tous les éléments de la sessions tel que stocké par la session
        // c'est à dire à l'intérieur de l'attribut
        dump($session->all());

        // Accéder à un élément de la session
        dump($session->get('prenom'));

        // supprimer un élément de la session
        $session->remove('nom');
        dump($session->all());

        // Pour vider la session
        $session->clear();

        dump($session->all());

        $session->set('user',['prenom'=>'GROUCHO', 'nom'=>'Marx']);

        dump($session->all());




        return $this->render('http/session.html.twig');
    }

    /**
     * @Route("/response")
     */
    public function response(Request $request)
    {
        // Toutes les méthodes des controlleurs doivent
        // retourner un objte instance de la classe Response
        $response = new Response('Contenu de la page ');

        //http/localhost:8000/http/response?type=twig
        if ($request->query->get('type') == 'twig'){

            // $this->render renvoi un objet instance de la classe Response
            //Dont le contenu est en HTML construit par le template
            return $this->render('http/response.html.twig');
        }

        return $response;
    }

















}
