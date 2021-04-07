<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route(path="/profilUser", name="profil_user", methods={"GET", "POST"})
     */
    public function profilUser(Request $request, EntityManagerInterface $entityManager){
        $profilUser =  new User();

        $profilUser->setPseudo($this->getUser()->getUsername());


        $profilUserForm = $this->createForm(UserType::class, $profilUser);

        // Visualiser dans le champs ce que l'on a recuperer
        $profilUserForm->handleRequest($request);

        //$profilUser = $entityManager->getRepository(User::class)->find(1);



        return $this->render('user/profilUser.html.twig', ['profilUserForm'=> $profilUserForm->createView()]);

    }















    public function index(): Response {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
