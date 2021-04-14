<?php

namespace App\Repository;

use App\Entity\Event;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;



/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    //Select avec une date de limite d'inscription suppérieur à la date du jour
    //Compris entre les intervalles 2-open et 3-open inclus
    public function getAll(){
        $date = new \DateTime();
        $req = $this->createQueryBuilder('event')
            ->andWhere('event.registrationLimit> :date')
            //->andWhere('event.status >= :begin')
            //->andWhere('event.status <= :end')
            ->setParameter('date', $date)
            //->setParameter('begin', 2)
            //->setParameter('end', 3)
            ->orderBy('event.registrationLimit', 'ASC');

        return $req->getQuery()->getResult();
    }

    /**
     * @throws -met la BDD à jour quand on revient sur l'accueil
     * passage en statut 3 -cloturé quand la date de fin d'inscription
     * a dépassée la date du jour
     */
    public function updateBDD(){
        $date = new \DateTime('NOW', new \DateTimeZone('EUROPE/Paris'));
        //Moins 1 jour sur la date du jour
        $date->modify("-1 day");

        //Recup des sorties avec une date limite d'inscription supérieur à la date du jour
        //Passage en status 3-Closed
        $req = $this->createQueryBuilder('event')
            ->update(Event::class, 'event')->set('event.status', '?1')
            ->where('event.registrationLimit< :date')
            ->setParameter(1, '3')
            ->setParameter('date', $date);
        $req->getQuery()->execute();
    }

    /**
     * @throws -met la BDD à jour quand on revient sur l'accueil
     * passage en statut 7 -archivé quand la date de fin d'inscription
     * a dépassée la date du jour
     */
    public function updateBDDArchive(){
        $date = new \DateTime('NOW', new \DateTimeZone('EUROPE/Paris'));
        //Moins 1 jour sur la date du jour
        $date->modify("-30 day");

        //Recup des sorties avec une date limite d'inscription supérieur à la date du jour
        //Passage en status 3-Closed
        $req = $this->createQueryBuilder('event')
            ->update(Event::class, 'event')->set('event.status', '?1')
            ->where('event.dateAndHour< :date')
            ->setParameter(1, '7')
            ->setParameter('date', $date);
        $req->getQuery()->execute();
    }

    //Les filtres sur les événement
    public function filterEvent($keyWord,$campus,$dateStart,$dateEnd,$userId,$eventOrganizer){
     //  $eventOrganizer,$eventSubscriber, $eventNotSubscriber, $eventOld

        $test='1340';

        // recuperer les méthodes du querybuilder (alias de l'entité)
        $qb = $this->createQueryBuilder('event');

        if(!empty($keyWord)){
            $qb ->andWhere('event.eventName LIKE :eventName')
                ->setParameter('eventName', '%'.$keyWord.'%')
            ;}

        if(!empty($campus)){
            $qb ->andWhere('event.campus = :campus')
                ->setParameter('campus', $campus)
            ;}

        // dateStart
        if((!empty($dateEnd) || !empty($dateStart))){
            $qb ->andWhere('event.dateAndHour > :dateAndHourStart')
                ->setParameter('dateAndHourStart', $dateStart)
            ;}

        // dateEnd
        if(!empty($dateEnd) || !empty($dateStart)){
            $qb ->andWhere('event.dateAndHour < :dateAndHourEnd')
                ->setParameter('dateAndHourEnd', $dateEnd)
            ;}

        // eventOrganizer
        // Si la checkbox est cocher (checkbox = true en base)
        if($eventOrganizer){
            $qb ->andWhere('event.organizer = :eventOrganizer')
                ->setParameter('eventOrganizer', $userId)
            ;}

        // eventSubscriber
//        if($eventSubscriber){
//            $qb ->andWhere('eventUser.userId = :eventSuscriber')
//                ->setParameter('eventSuscriber', $userSub)
//            ;}
//
//        // eventNotSubscriber
//        if($eventNotSubscriber == true){
//            $qb ->andWhere('event = :')
//                ->setParameter('dateAndHour', $dateEnd)
//            ;}
//
//        // eventOld
//        if($eventNotSuscriber == true){
//            $qb ->andWhere('event = :')
//                ->setParameter('dateAndHour', $dateEnd)
//            ;}

        return $qb->getQuery()->getResult();
        }

    }