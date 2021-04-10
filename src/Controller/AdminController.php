<?php


namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Cities;
use Doctrine\ORM\EntityManagerInterface;
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
    public function campus(EntityManagerInterface $em){
        if($this->getUser()){
            //à optimiser avec une méthode en repository
            $campus = $em->getRepository(Campus::class)->findAll();
        }
        return $this->render('admin/campus.html.twig', ['campus'=>$campus]);
    }

    /**
     * @Route(path="/ville", name="ville")
     */
    public function city(EntityManagerInterface $em){

        if($this->getUser()){
            //à optimiser avec une méthode en repository
            $cities = $em->getRepository(Cities::class)->findAll();
        }
        return $this->render('admin/city.html.twig', ['cities'=>$cities]);
    }

}