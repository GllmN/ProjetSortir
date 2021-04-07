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

            //Si l'utilisateur clique sur le bouton Enregistrer('save')
            //La sortie est enregistrée en BDD avec le statut created(id1)
            if ($form->get('save')->isClicked()){
                $statusCreate = $entityManager->getRepository(EventStatus::class)->find(1);
                $event->setStatus($statusCreate);
                $entityManager->persist($event);
                $entityManager->flush();
                //$this->addFlash('success','Sortie créée mais non publier !');
            }
            //Si l'utilisateur clique sur le bouton Publier('publish')
            //La sortie est enregistrée en BDD avec le statut open(id2)
            elseif($form->get('publish')->isClicked()){
                $statusCreate = $entityManager->getRepository(EventStatus::class)->find(2);
                $event->setStatus($statusCreate);
                $entityManager->persist($event);
                $entityManager->flush();
                //$this->addFlash('success','Sortie publiée !');
            }
            elseif ($form->get('cancel')->isClicked()){

                return $this->render('home/home.html.twig');
            }


            //return $this->redirectToRoute();
        }

        return $this->render('events/create.html.twig', ['eventForm'=>$form->createView()]);

    }

    /**
     * @Route(path="/afficher", name="afficher")
     */
    public function list(EntityManagerInterface $entityManager){
        $event = $entityManager->getRepository('App:Event')->getAll();
        return $this->render('home/home.html.twig', ['list'=>$event]);
    }
}