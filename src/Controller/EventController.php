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
 * @Route(path="/Accueil", name="Accueil_")
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

        return $this->render('sorties/create.html.twig', ['eventForm'=>$form->createView()]);


    }



}