<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route(path="deconnexion", name="deconnexion", methods={"GET"})
     */
    public function deconnexion() {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route(path="home", name="home", methods={"GET"})
     */
    public function home() {
        return $this->render('home/home.html.twig');
    }

}