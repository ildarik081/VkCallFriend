<?php

namespace App\DataFixtures;

use App\Component\Utils\Aliases;
use App\Entity\CallStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (Aliases::CALL_STATUS as $status) {
            $callStatus = new CallStatus();
            $callStatus->setValue($status['value']);
            $callStatus->setDescription($status['description'] ?? null);
            $manager->persist($callStatus);
        }

        $manager->flush();
    }
}
