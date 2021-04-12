<?php


namespace App\EventListener;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class EventUpdateBDD implements EventSubscriber
{


    /**
     * EventUpdateBDD constructor.
     * @param $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
            Events::postLoad
        ];
    }

    public function prePersist(LifecycleEventArgs $args){
        dd($args);
    }

    public function preUpdate(LifecycleEventArgs $args){
        echo "preUpdate";
    }

    public function preRemove(LifecycleEventArgs $args){
        echo "preRemove";
    }

    public function postLoad(LifecycleEventArgs $args){


    }



}