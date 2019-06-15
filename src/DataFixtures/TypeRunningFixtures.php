<?php

namespace App\DataFixtures;

use App\Entity\TypeRunning;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TypeRunningFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types = ['training', 'running', 'spare-time', 'advanced preparation'];

        foreach ($types as $type) {
            $runningType = new TypeRunning();
            $runningType->setName($type);

            $manager->persist($runningType);
        }

        $manager->flush();
    }
}
