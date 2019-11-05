<?php


namespace App\Service;


use App\Repository\TicketRepository;

class TicketRegistrationService
{
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function ticketCountForAssetIdIsLessThanThree($assetId)
    {
        return sizeof($this->ticketRepository->findBy(['assetId' => $assetId])) < 3;
    }
}