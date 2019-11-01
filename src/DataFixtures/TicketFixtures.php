<?php

namespace App\DataFixtures;

use App\Entity\Asset;
use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TicketFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $assetRepository = $manager->getRepository(Asset::class);
        $numberOfVotes = 100;
        $counter = 1;
        for ($i = 1; $i <= sizeof($assetRepository->findAll()); $i++) {
            $this->loadTicket($manager, $assetRepository->find($i), $numberOfVotes, "Ticket no. $counter");
            $counter++;
            $numberOfVotes = $numberOfVotes + 100 * $i;
        }
    }

    private function loadTicket(ObjectManager $manager, $asset, $numberOfVotes, $description)
    {
        $ticket = new Ticket();
        $ticket->setAsset($asset);
        $ticket->setNumberOfVotes($numberOfVotes);
        $ticket->setDescription($description);

        $manager->persist($ticket);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            RoomFixtures::class,
            AssetFixtures::class
        ];
    }
}
