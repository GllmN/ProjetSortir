<?php


namespace App\Controller;


use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="", name="accueil")
 */
class EventController extends AbstractController
{
    /**
     * @Route(path="/create", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager) : Response{
        $event = new Event();
        $event->setDateAndHour(new \DateTime());
        $event->setRegistrationLimit(new \DateTime());


        $form= $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $event->setStatus(1);
            $entityManager->persist($event);
            $entityManager->flush();
        }

        return $this->render('events/create.html.twig', ['eventForm'=>$form->createView()]);

    }

    /**
     * @Route(path="/accueil")
     */
    public function list(EntityManagerInterface $entityManager){
        $event = $entityManager->getRepository('App:Event')->findAll();
        return $this->render('events/event.html.twig', ['list'=>$event]);
    }



}