<?php


namespace App\Controller;


use App\Entity\Event;
use App\Form\FilterType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="accueil", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route(path="", name="home", methods={"GET"})
     */
    public function home(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request) {

        $em->getRepository(Event::class)->updateBDD();
        if ($this->getUser()) {
            //création d'un formulaire filter dans l'acceuil
            $filterForm = $this->createForm(FilterType::class);
            $donnes = $em->getRepository(Event::class)->getAll();
            $event = $paginator->paginate(
                $donnes,
                $request->query->getInt('page', 1),
                5
            );
            return $this->render('home/home.html.twig',
                ['list' => $event,'filterForm' => $filterForm->createView()]);
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