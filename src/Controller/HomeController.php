<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route(path="home", name="home", methods={"GET"})
     */
    public function home(EntityManagerInterface $entityManager) {

        $event = $entityManager->getRepository('App:Event')->getAll();
        return $this->render('home/home.html.twig', ['list'=>$event]);

    }

    /**
     * @Route(path="profil", name="profil", methods={"GET"})
     */
    public function profil() {
        return $this->render('user/profilUser.html.twig');
    }



}