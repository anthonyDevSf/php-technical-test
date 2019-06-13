<?php

namespace App\EventListener;

use App\Entity\Running;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class CalculSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Running) {
            return;
        }

        $entityManager = $args->getObjectManager();

        $this->calculAverageSpeed($entity);
        $this->calculAveragePace($entity);

        $entityManager->persist($entity);
        $entityManager->flush();
    }

    /**
     * Formule to get average speed : V = D / H
     * D = Distance (km)
     * H = Hours
     *
     * @param Running $running
     */
    private function calculAverageSpeed(Running $running)
    {
        // we need only the hours
        $hours        = $running->getDuration()->format('H') + $running->getDuration()->format('i') / 60;
        // we apply the formule with a precision of 1 number
        $averageSpeed = round($running->getDistance() / $hours, 1);

        $running->setSpeed($averageSpeed);
    }

    /**
     * Formule to get average pace from average speed : P = 60 / S
     * 60 = convert hours to minutes
     * S  = average speed
     *
     * @param Running $running
     */
    private function calculAveragePace(Running $running)
    {
        // we apply the formule with a precision of 2 number
        $averagePace = round(60 / $running->getSpeed(), 2);

        $running->setPace($averagePace);
    }
}
