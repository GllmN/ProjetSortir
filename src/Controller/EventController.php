<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\EventStatus;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\LocationRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/sortie", name="sortie_")
 */
class EventController extends AbstractController
{
    /**
     * @Route(path="/creation", name="creation")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, LocationRepository $repository) : Response{
        //status:
        //1-created
        //2-open
        //3-closed
        //4-in progresse
        //5-finished
        //6-canceled
        //7-archived

        $event = new Event();
        $event->setDateAndHour(new DateTime('NOW', new DateTimeZone('EUROPE/Paris')));
        $event->setRegistrationLimit(new DateTime('NOW', new DateTimeZone('EUROPE/Paris')));
        //Récup de l'user connecté avec son id
        $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId()) ;

        //modif de l'objet avec l'organisateur
        $event->setOrganizer($user);

        $form= $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //Si l'utilisateur clique sur le bouton Enregistrer('save')
            //La sortie est enregistrée en BDD avec le statut created(id1)
            if ($form->get('save')->isClicked()){
                $statusCreate = $entityManager->getRepository(EventStatus::class)->find(1);

                $this->addFlash('success','Sortie créée mais non publier !');
            }
            //Si l'utilisateur clique sur le bouton Publier('publish')
            //La sortie est enregistrée en BDD avec le statut open(id2)
            elseif($form->get('publish')->isClicked()){
                $statusCreate = $entityManager->getRepository(EventStatus::class)->find(2);

                $this->addFlash('success','Sortie publiée !');
            }
            $event->setStatus($statusCreate);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('home_home');
        }

        $location = $repository->findAll();
        //dump($location);
        //$json = serialize($location);
        //$test = json_encode($json);
        //$test = $serializer->serialize($location, 'json');
        //$rep = new JsonResponse();
        //$rep->setContent($test);


        return $this->render('events/create.html.twig', ['eventForm'=>$form->createView(), 'location'=>$location]);
    }

    /**
     * @Route(path="/afficher", name="afficher")
     */
    public function detail(Request $request, EntityManagerInterface $entityManager){
        $id = $request->get('id');
        $event = $entityManager->getRepository(Event::class)->find($id);

        return $this->render('events/detail.html.twig', ['event'=>$event]);
    }

    /**
     * @Route(path="/modifier", name="modifier")
     */
    public function modify(Request $request, EntityManagerInterface $entityManager, LocationRepository $repository){
        $id = $request->get('id');
        $event = $entityManager->getRepository(Event::class)->find($id);

        $form= $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //Si l'utilisateur clique sur le bouton Enregistrer('save')
            //La sortie est enregistrée en BDD avec le statut created(id1)
            if ($form->get('save')->isClicked()){
                $statusCreate = $entityManager->getRepository(EventStatus::class)->find(1);

                $this->addFlash('success','Sortie modifiée !');
            }
            //Si l'utilisateur clique sur le bouton Publier('publish')
            //La sortie est enregistrée en BDD avec le statut open(id2)
            elseif($form->get('publish')->isClicked()){
                $statusCreate = $entityManager->getRepository(EventStatus::class)->find(2);

                $this->addFlash('success','Sortie publiée !');
            }
            $event->setStatus($statusCreate);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('home_home');
        }

        $location = $repository->findAll();

        return $this->render('events/modify.html.twig', ['eventForm'=>$form->createView(), 'location'=>$location]);
    }


    /**
     * @Route(path="/registration", name="registration")
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function registration(EntityManagerInterface $entityManager){

        /** @var Event $event */
        $event = $entityManager->getRepository(Event::class)->find($_GET['id']);

        $today = new DateTime();

        $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());


        if ($event->getParticipants()->contains($user)) {
            $this->addFlash('warning', "Vous êtes déja inscrit à cet event ! ");
            return $this->redirectToRoute("home_home");
        }


        if ($event->getNumberOfPlaces() >= 1 && $event->getRegistrationLimit() > $today){

            $event->addParticipant($this->getUser());


            $event->setNbRegistration($event->getNbRegistration() + 1);
            $event->setNumberOfPlaces($event->getNumberOfPlaces() - 1);


            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', "Bien joué tu as été inscrit !!");
            return $this->redirectToRoute('home_home');

        } else {
            $this->addFlash('danger', "Inscription impossible ou alors il n'y a plus de places !!!");
            return $this->redirectToRoute('home_home');
        }
        return $this->render('home/home.html.twig');
        //return $this->redirectToRoute('home_home');
    }

    /**
     * @Route(path="/removeRegistration", name="remove_registration")
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removeRegistration(EntityManagerInterface $entityManager){

        /** @var Event $event */
        $event = $entityManager->getRepository(Event::class)->find($_GET['id']);

        $today = new DateTime();

        $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());


        if ($event->getParticipants()->contains($user)) {


            $event->removeParticipant($this->getUser());

            $event->setNbRegistration($event->getNbRegistration() - 1);
            $event->setNumberOfPlaces($event->getNumberOfPlaces() + 1);


            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', "Bien joué tu as été désinscrit !!");
            return $this->redirectToRoute("home_home");
        }
            else {
            $this->addFlash('danger', "Annulation impossible ou alors tu n'es pas déjà inscrit à l'event");
            return $this->redirectToRoute('home_home');
        }
        return $this->render('home/home.html.twig');
        //return $this->redirectToRoute('home_home');
    }



}
