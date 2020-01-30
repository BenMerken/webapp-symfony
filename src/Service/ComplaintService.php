<?php


namespace App\Service;


use App\Repository\ComplaintRepository;
use App\Repository\UserRepository;

class ComplaintService
{
    private $complaintRepository;
    private $userRepository;

    public function __construct(ComplaintRepository $complaintRepository, UserRepository $userRepository)
    {
        $this->complaintRepository = $complaintRepository;
        $this->userRepository = $userRepository;
    }

    public function getComplaintsForUserEmail($email)
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $complaints = $this->complaintRepository->findBy(['user' => $user]);

        return $complaints;
    }
}
