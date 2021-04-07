<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\EventStatus;
use App\Form\EventType;
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
    public function create(Request $request, EntityManagerInterface $entityManager) : Response{
        $event = new Event();
        $event->setDateAndHour(new \DateTime());
        $event->setRegistrationLimit(new \DateTime());

        $form= $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $statusCreate = $entityManager->getRepository(EventStatus::class)->find(1);
            $event->setStatus($statusCreate);
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success','Sortie créée !');
            //return $this->redirectToRoute();
        }

        return $this->render('events/create.html.twig', ['eventForm'=>$form->createView()]);

    }

    /**
     * @Route(path="/afficher", name="afficher")
     */
    public function list(EntityManagerInterface $entityManager){
        $event = $entityManager->getRepository('App:Event')->getAll();
        return $this->render('events/event.html.twig', ['list'=>$event]);
    }

}