<?php


namespace App\Controller;


use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\EventStatus;
use App\Entity\User;
use App\Form\EventCancelType;
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
    public function create(Request $request, EntityManagerInterface $em, LocationRepository $repository) : Response{
        //status:
        //1-created
        //2-open
        //3-closed
        //4-in progress
        //5-finished
        //6-canceled
        //7-archived

        $dateDuJour = new DateTime('NOW', new DateTimeZone('EUROPE/Paris'));
        //-1 jour sur la date courante pour pouvoir créer une sortie pour le jour même
        $dateDuJour->modify('-1 day');

        $event = new Event();
        $event->setDateAndHour(new DateTime('NOW', new DateTimeZone('EUROPE/Paris')));
        $event->setRegistrationLimit(new DateTime('NOW', new DateTimeZone('EUROPE/Paris')));

        //Récup de l'user connecté avec son id
        $user = $em->getRepository(User::class)->find($this->getUser()->getId()) ;

        //modif de l'objet avec l'organisateur et le Campus
        $event->setOrganizer($user);

        $event->setCampus($user->getCampus());

        $form= $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        //Controle sur la date pour ne pas créer de sorties dans la passé
        //Contrôle sur date de sortie et date limite d'inscription
        if($form->isSubmitted() &&  ($event->getDateAndHour()<=$dateDuJour || $event->getRegistrationLimit()<=$dateDuJour)){
            $this->addFlash('danger', 'Tu n\'as pas le pouvoir de voyager dans le temps');
            $this->redirectToRoute('sortie_creation');
        }

        if($form->isSubmitted() && $form->isValid() && ($event->getDateAndHour()>$dateDuJour) && ($event->getRegistrationLimit()>$dateDuJour)){

            $event->setInitialPlaces(($event->getNumberOfPlaces()));

            //Si l'utilisateur clique sur le bouton Enregistrer('save')
            //La sortie est enregistrée en BDD avec le statut created(id1)
            if ($form->get('save')->isClicked()){
                $statusCreate = $em->getRepository(EventStatus::class)->find(1);

                $this->addFlash('success','Sortie créée mais non publier !');
            }
            //Si l'utilisateur clique sur le bouton Publier('publish')
            //La sortie est enregistrée en BDD avec le statut open(id2)
            elseif($form->get('publish')->isClicked()){
                $statusCreate = $em->getRepository(EventStatus::class)->find(2);
                $this->addFlash('success','Sortie publiée !');
            }
            $event->setStatus($statusCreate);
            $em->persist($event);
            $em->flush();

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
        //Récup de l'id de l'event via l'URL
        $id = $request->get('id');
        $event = $entityManager->getRepository(Event::class)->find($id);
        return $this->render('events/detail.html.twig', ['event'=>$event]);
    }

    /**
     * @Route(path="/modifier", name="modifier")
     */
    public function modify(Request $request, EntityManagerInterface $entityManager, LocationRepository $repository){
        $dateDuJour = new DateTime('NOW', new DateTimeZone('EUROPE/Paris'));
        //-1 jour sur la date courante pour pouvoir créer une sortie pour le jour même
        $dateDuJour->modify('-1 day');

        //Récup id de l'utilisateur connecté
        $userId = $entityManager->getRepository(User::class)->find($this->getUser());
        $userId = $userId->getId();

        //Récup de l'id de l'event
        $eventId = $request->get('id');
        $event = $entityManager->getRepository(Event::class)->find($eventId);

        //Récup de l'oraginsateur
        $organizer = $event->getOrganizer()->getId();

        $form= $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        //Controle sur la date pour ne pas créer de sorties dans la passé
        //Contrôle sur date de sortie et date limite d'inscription
        if($form->isSubmitted() &&  ($event->getDateAndHour()<=$dateDuJour || $event->getRegistrationLimit()<=$dateDuJour)){
            $this->addFlash('danger', 'Tu n\'as pas le pouvoir de voyager dans le temps');
            $this->redirectToRoute('sortie_creation');
        }

        //Si l'utilisateur connecté n'est pas l'organisateur de la sortie
        //l'Utilisateur ne peut pas modifier la sortie
        if ($userId !== $organizer){
            $this->addFlash('danger', 'FAIL!!!! Tu n\'es pas l\'organisateur de cette sortie');
            return $this->redirectToRoute('sortie_creation');
        } elseif ($form->isSubmitted() && $form->isValid()){
            $event->setInitialPlaces(($event->getNumberOfPlaces()));

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

                $this->addFlash('success','T\'es un BOSS !! Sortie publiée !');
            }
            $event->setStatus($statusCreate);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('home_home');
        }

        $location = $repository->findAll();

        return $this->render('events/modify.html.twig', ['eventForm'=>$form->createView(), 'location'=>$location, 'eventId'=>$eventId]);
    }

    /**
     * @Route(path="/annuler", name="annuler")
     */
    public function cancel(Request $request, EntityManagerInterface $em){
        //Récup de l'id de l'event par l'URL
        $id = $request->get('id');
        $event = $em->getRepository(Event::class)->find($id);

        $form= $this->createForm(EventCancelType::class);
        $form->handleRequest($request);

        if ($form->get('cancel')->isClicked()){
            return $this->redirectToRoute('home_home');
        }

        if ($form->isSubmitted() && $form->isValid()){
            if  ( $form->get('save') && $form->get('motif')->isEmpty()){
                $this->addFlash('danger', 'Il nous faut un motif!!!');
            }elseif ($form->get('save')->isClicked()){
                //mise à jour de la sortie
                //Avec statut 6-Canceled
                $statusCreate = $em->getRepository(EventStatus::class)->find(6);
                $event->setStatus($statusCreate);
                $em->persist($event);
                $em->flush();

                $this->addFlash('success', 'La sortie a été annulée!');
                return $this->redirectToRoute('home_home');
            }
        }
        return $this->render('events/annuler.html.twig', ['eventCancel'=> $form->createView(), 'event'=> $event]);
    }

    /**
     * @Route(path="/registration", name="registration")
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function registration(EntityManagerInterface $entityManager){
        // on récupère l'id de l'event
        /** @var Event $event */
        $event = $entityManager->getRepository(Event::class)->find($_GET['id']);

        $today = new DateTime();
        // on récupère l'id de l'utilisateur
        $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());

        // si la liste des participants contient notre User, affiche un message warning !
        if ($event->getParticipants()->contains($user)) {
            $this->addFlash('warning', "Vous êtes déja inscrit à cet event ! ");
            return $this->redirectToRoute("home_home");
        }

        //Récup statut de la sortie
        $statutEvent = $event->getStatus()->getId();

        // si le nombre de places est supérieur ou égale à 1 et que la date limite d'inscription est supérieur à la date du jour..
        //Plus vérification du statut 2-ouvert
        if ($event->getNumberOfPlaces() >= 1 && $event->getRegistrationLimit() > $today && $statutEvent == 2 ){
        // On ajoute le user à la liste des participants..
            $event->addParticipant($this->getUser());

        //On ajoute +1 au nombre d'inscrit
            $event->setNbRegistration($event->getNbRegistration() + 1);
        // on retire -1 au nombre de place
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

        // Si l'utilisateur fait partie de la liste des participants alors..
        //Récup statut de la sortie
        $statutEvent = $event->getStatus()->getId();

        //Vérif si la sortie est en statut 2-ouverte ou 3-cloturé
        if ($event->getParticipants()->contains($user) && $statutEvent == 2 || $statutEvent == 3) {

            // On retire le user à la liste des participants..
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
