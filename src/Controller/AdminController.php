<?php


namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Cities;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function city(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request){

        if($this->getUser()){

            $donnes = $em->getRepository(Cities::class)->getAll();
            //Pagination avec "composer req knplabs/knp-paginator-bundle"
            $cities = $paginator->paginate(
                $donnes,
                $request->query->getInt('page', 1),
                20
            );

        }
        return $this->render('admin/city.html.twig', ['cities'=>$cities]);
    }

}