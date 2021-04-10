<?php


namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="accueil", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route(path="", name="home", methods={"GET"})
     */
    public function home(EntityManagerInterface $entityManager) {

        if ($this->getUser()) {
            $event = $entityManager->getRepository('App:Event')->getAll();
            return $this->render('home/home.html.twig', ['list' => $event]);
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route(path="profil", name="profil", methods={"GET"})
     */
    public function profil() {
        return $this->render('user/profilUser.html.twig');
    }
}