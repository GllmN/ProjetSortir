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
        // Déclaration des variables et instances
        $profilUser = new User();

        // Récupération des données de l'utilisateur
        $profilUser = $this->getUser();

        // Si les données sont récupérees = user connecté
        if ($profilUser) {
            // Récupération des données liées à l'utilisateur
            $profilUserForm = $this->createForm(UserType::class, $profilUser);

            // Visualisation dans le champs de ce que l'on a recuperer
            $profilUserForm->handleRequest($request);

            // Si le champs est valide et soumis
            if ($profilUserForm->isSubmitted() && $profilUserForm->isValid()){
                // On stocke les données à envoyer sur la BDD
                $updateProfil = $entityManager->getRepository(User::class);
                // On envoie sur la BDD
                $entityManager->persist($profilUser);
                $entityManager->flush();
                // On affiche un message de succes
                $this->addFlash('success', 'Profil mise à jour!');
            }

            // Affichage des données dans le formulaire
            return $this->render('user/profilUser.html.twig', ['profilUserForm'=> $profilUserForm->createView()]);
        }
        // Redirection sur la page de login si les données de l'utilisateur ne sont pas récupérés
        return $this->redirectToRoute('app_login');





    }















    public function index(): Response {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
