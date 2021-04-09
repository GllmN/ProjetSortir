<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\EventStatus;
use App\Entity\Location;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function create(Request $request, EntityManagerInterface $entityManager, LocationRepository $repository, SerializerInterface $serializer) : Response{
        //status....

        $event = new Event();
        $event->setDateAndHour(new \DateTime());
        $event->setRegistrationLimit(new \DateTime());
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

                //$this->addFlash('success','Sortie créée mais non publier !');
            }
            //Si l'utilisateur clique sur le bouton Publier('publish')
            //La sortie est enregistrée en BDD avec le statut open(id2)
            elseif($form->get('publish')->isClicked()){
                $statusCreate = $entityManager->getRepository(EventStatus::class)->find(2);

                //$this->addFlash('success','Sortie publiée !');
            }
            $event->setStatus($statusCreate);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('home_home');
        }

        $location = $repository->findAll();
        return $this->render('events/create.html.twig', ['eventForm'=>$form->createView(), 'location'=>$location]);
    }

    /**
     * @Route(path="/afficher", name="afficher")
     */
    public function detail(EntityManagerInterface $entityManager){
        $event = $entityManager->getRepository('App:Event')->getAll();
        return $this->render('home/home.html.twig', ['list'=>$event]);
    }


    /**
     * @Route(path="/registration", name="registration")
     */
    public function registration(EntityManagerInterface $entityManager, Request $request){

        /** @var Event $event */
        $event = $entityManager->getRepository(Event::class)->find($_GET['id']);




        $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());



        if(count($event->getParticipants()) < $event->getNumberOfPlaces())
        {

            $event->addParticipant($this->getUser());
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', "Bien joué tu as été inscris !!");
            return $this->redirectToRoute('home_home');
        }
        else
        {
            $this->addFlash('danger', "Plus de places !!!");
        }
        return $this->render('home/home.html.twig');
        //return $this->redirectToRoute('home_home');
    }

}