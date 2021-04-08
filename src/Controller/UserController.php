<?php

namespace App\Controller;

use App\Entity\EventStatus;
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
        $profilUser = $this->getUser();

        if ($profilUser) {

            $profilUserForm = $this->createForm(UserType::class, $profilUser);

            // Visualiser dans le champs ce que l'on a recuperer
            $profilUserForm->handleRequest($request);

            if ($profilUserForm->isSubmitted() && $profilUserForm->isValid()){
                $updateProfil = $entityManager->getRepository(User::class);
                $entityManager->persist($profilUser);
                $entityManager->flush();
                $this->addFlash('success', 'Profil mise à jour!');
            }
            return $this->render('user/profilUser.html.twig', ['profilUserForm'=> $profilUserForm->createView()]);

        }

        return $this->redirectToRoute('app_login');





    }















    public function index(): Response {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
