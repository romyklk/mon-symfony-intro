<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        //Tous les éléments de la sessions tel que stockés par la session
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
        // retourner un objet instance de la classe Response
        $response = new Response('Contenu de la page ');

        //http/localhost:8000/http/response?type=twig
        if ($request->query->get('type') == 'twig'){

            // $this->render renvoi un objet instance de la classe Response
            //Dont le contenu est en HTML construit par le template
            return $this->render('http/response.html.twig');
        }
        //http/localhost:8000/http/response?type=json
        elseif ($request->query->get('type') == 'json'){
            $response = [
                'nom'=>'Marx',
                'prenom'=>'GROUCHO'
            ];
            // Return new Response(json_encode($response));
            // Encode le tableau $response en json
            //et retourne reponse avec l'entête HTTP content type application/json
            return new JsonResponse($response);
            //http/localhost:8000/http/response?found=no
        }elseif ($request->query->get('found') == 'no'){
            // Pour retourner une 404, jette cette exception
            throw new NotFoundHttpException();
            //http/localhost:8000/http/response?redirect=index
        }elseif ($request->query->get('redirect') == 'index'){

            //redirection en passant le nom de la route dans la page:
            //app_index_index : IndexController::index()
            return $this->redirectToRoute('app_index_index');

        }elseif ($request->query->get('redirect') == 'bonjour'){

            // rediection avec une route qui contient une partie variable
            return $this->redirectToRoute(
                'app_index_bonjour',
                [
                    'qui'=>'le monde'
                ]
            );
            }

        return $response;
    }

    /**
     * @Route("/flash")
     */
    public function flash()
    {
        //Enrégistrer un message dans la session
        $this->addFlash('success','Message de confirmation');
        $this->addFlash('success','Deuxieme message de confirmation');
        $this->addFlash('error','Message d\'erreur ');
        $this->addFlash('error2','Message erreur2 ');

        return $this->redirectToRoute('app_http_flashed');
    }

    /**
     * @Route("/flashed")
     */
  public function flashed(SessionInterface $session)
  {
      dump($session->all());

      dump($_SESSION);


      return $this->render('http/flashed.html.twig');
  }

    /*
     * Faire une page avec un formulaire en post avec :
     * - email (text)
     * - message (textarea)
     *
     * Si le formulaire est envoyé, vérifier que les deux champs sont remplis
     * Si non, afficher un message d'erreur
     * Si oui, enregistrer les valeurs en session et rediriger vers
     * une nouvelle page qui les affiche et vide la session
     * Dans cette page, si la session est vide, on redirige vers le formulaire
     */

    /**
     * @return Response
     * @Route("/formu")
     */
  public function formu(Request $request)
  {
      $erreur = '';

      // Si le formulaire a été envoyé
      if($request->isMethod('POST')){
          // $_POST['email'] et $_POST['nom']
          $email = $request->request->get('email');
          $message = $request->request->get('message');

          if(!empty($email) && !empty($message)){
              $session = $request->getSession();
              $session->set('email',$email);
              $session->set('message',$message);

              return $this->redirectToRoute('app_http_veriform');

          }else{
              $erreur = 'Tous les champs sont obligatoires';
          }

      }

      return $this->render('http/formu.html.twig',['erreur'=>$erreur]);
  }

    /**
     * @return Response
     * @Route("/veriForm")
     */
  public function veriForm(SessionInterface $session)
  {
      if(empty($session->all())){
          return $this->redirectToRoute('app_http_formu');
      }
      $email = $session->get('email');
      $message = $session->get('message');

      // Pour vider la session
      $session->clear();

      return $this->render('http/veriForm.html.twig',['email'=>$email,'message'=>$message]);
  }

}
