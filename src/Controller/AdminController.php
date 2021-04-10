<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/admin", name="admin_")
 */
class AdminController extends AbstractController{

    /**
     * @Route(path="", name="accueil")
     */
    public function admin(){

       return $this->render('admin/admin.html.twig');
    }

    /**
     * @Route(path="/campus", name="campus")
     */
    public function campus(){

        return $this->render('admin/campus.html.twig');
    }

    /**
     * @Route(path="/ville", name="ville")
     */
    public function city(){

        return $this->render('admin/city.html.twig');
    }

}