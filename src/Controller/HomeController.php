<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\User;
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
     * @Route(path="", name="home", methods={"GET", "POST"})
     */
    public function home(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request) {

        //Verification des dates de fin d'inscription / à la date du jour
        $em->getRepository(Event::class)->updateBDD();
        $em->getRepository(Event::class)->updateBDDArchive();

        //Récup id de l'utilisateur connecté
        $userId = $em->getRepository(User::class)->find($this->getUser());
        $userId = $userId->getId();

        //Récup de l'id de l'event
        //$eventId = $request->get('id');
        //$userSub = $em->getRepository(Event::class)->find($eventId);

        //Récup id de l'utilisateur connecté

        //création d'un formulaire filter dans l'acceuil
        $filterForm = $this->createForm(FilterType::class);

        $filterForm->handleRequest($request);

        // Filtrer les sorties , recherche par mot clé
        if ($filterForm->isSubmitted() && $filterForm->isValid()){

            $campus = $filterForm['campus']->getData();
            $keyWord = $filterForm['keyWord']->getData();
            $dateStart = $filterForm['dateStart']->getData();
            $dateEnd = $filterForm['dateEnd']->getData();
            $eventOrganizer = $filterForm['eventOrganizer']->getData();

            //$eventSubscriber = $filterForm['eventSubscriber']->getData();
            //$eventNotSubscriber = $filterForm['eventSubscriber']->getData();
            //$eventOld = $filterForm['eventOld']->getData();

            if($dateEnd > $dateStart) {
                $result = $em->getRepository(Event::class)
                    ->filterEvent($keyWord, $campus, $dateStart ,$dateEnd, $userId ,$eventOrganizer);
            } else{
                $this->addFlash('warning', "Hello, pour plus de précisions renseigner les dates ;-)");
            };

            $result = $em->getRepository(Event::class)
                ->filterEvent($keyWord, $campus , $dateStart ,$dateEnd,  $userId, $eventOrganizer);

            // Pagination /!\ remplacer le $result par le $event dans le render
                //            $event = $paginator->paginate(
                //                $result,
                //                $request->query->getInt('page', 1),
                //                5
                //            );

            return $this->render('home/home.html.twig', ['list' => $result,'filterForm' => $filterForm->createView()]);
        }

        // Récuperer les utilisateur
        if ($this->getUser()) {
            $donnes = $em->getRepository(Event::class)->getAll();

//            // Pagination
//            $event = $paginator->paginate(
//                $donnes,
//                $request->query->getInt('page', 1),
//                5
//            );

            return $this->render('home/home.html.twig', ['list' => $donnes,'filterForm' => $filterForm->createView()]);
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