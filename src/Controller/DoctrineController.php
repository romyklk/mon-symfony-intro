<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DoctrineController
 * @package App\Controller
 * @Route("/doctrine")
 */
class DoctrineController extends AbstractController
{
    /**
     * @Route("/doctrine", name="doctrine")p
     */
    public function index()
    {
        return $this->render('doctrine/index.html.twig', [
            'controller_name' => 'DoctrineController',
        ]);
    }

    /**
     * @return object|\Symfony\Component\Security\Core\User\UserInterface |void|null
     * @Route("/user/{id}", requirements={"id": "\d+"})
     */
    public function getOneUser(UserRepository $repository,$id)
    {
      /*
       * Retourne un objet User dont les attributs sont séttés
       * à partir de la bdd dans la table user à l'id passé en paramètre
       * ou null si l'id n'existe pas dans la table
       */
      $user = $repository->find($id);

      dump($user);
      //si l'id n'existe pas dans la table
        if(is_null($user)){
            throw new NotFoundHttpException();
        }

      return $this->render('doctrine/get_user.html.twig',['user'=>$user]);
    }

    /**
     * @Route("/users-list")
     */
    public function listUsers(UserRepository $repository)
    {
        /**
         * Retourne tous les utilisateurs de la table user ous forme d'un tableau
         */
        $users = $repository->findAll();

        return $this->render('doctrine/list_users.html.twig',['users'=>$users]);
    }

    /**
     * @Route("/search-email")
     */
    public function searchEmail(Request $request,UserRepository $repository)
    {
        $twigvariables = [];

        // if(isset($_GET['email'])) s'écrit
        if($request->query->has('email')){
            // findOneby quand on st sûr qu'il n'y aura pas plus d'un élément
            //Retourne un objet User ou null
            $user = $repository->findOneBy([
                'email'=> $request->query->get('email')
            ]);
            $twigvariables['user'] = $user;
        }

        return $this->render('doctrine/search_email.html.twig',$twigvariables);
    }

    /**
     * @Route("/pseudo/user/{pseudo}")
     */
    public function getByPseudo(UserRepository $repository, $pseudo)
    {
        $users = $repository->findBy([
            'pseudo'=>$pseudo
        ]);


        return $this->render('doctrine/list_users.html.twig',
            ['users' => $users]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/create-user")
     */
    public function createUser(Request $request, EntityManagerInterface $manager)
    {
        // si le formulaire est envoyé
        if($request->isMethod('POST')){

            // $data récupère tout ce qui est envoyer par $_POST
            $data = $request->request->all();

            $user = new User();
            // on set les données reçu
            $user
                ->setPseudo($data['pseudo'])
                ->setEmail($data['email'])
                // le setter de birthdate attend un objet DateTime
                ->setBirthdate(new \DateTime($data['birthdate']))
            ;

            // Prépare l'enrégistrement en bdd c-a-d qu'il indique au gestionnaire
            // d'entité qu'il faudra enrégistrer le User en bdd au prochain flus
            $manager->persist($user);

            // pour enrégistrer définitivement en bdd
            $manager->flush();

        }

        return $this->render('doctrine/create_user.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/search")
     */
    public function search(Request $request,UserRepository $repository)
    {

        $twigvariables = [];

        // if(isset($_GET['email'])) s'écrit
        if($request->query->has('search')){
            // findOneby quand on st sûr qu'il n'y aura pas plus d'un élément
            //Retourne un objet User ou null
            $users = $repository->findByPseudoOrEmail(
                $request->query->get('search'));

            $twigvariables['users'] = $users;
            dump($request->query->get('search'));
        }

        return $this->render('doctrine/search.html.twig',$twigvariables);
    }

}














