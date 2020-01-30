<?php

namespace App\DataFixtures;

use App\Entity\Complaint;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ComplaintFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $complaint = new Complaint();
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'custodian2@pxl.be']);

        $complaint->setUser($user);
        $complaint->setReason("Custodian 2 abused his privileges as a custodian to upvote multiple tickets more than a hundred times.");

        $manager->persist($complaint);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
