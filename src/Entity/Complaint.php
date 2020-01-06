<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComplaintRepository")
 */
class Complaint
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="complaints")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $reason;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @Assert\Callback()
     */
    public function validateDescription(ExecutionContextInterface $context)
    {
        if (!is_string($this->reason)) {
            $context->buildViolation('The reason for your complaint must be a string.')
                ->atPath('reason')->addViolation();
        }

        if (!(strlen($this->reason) >= 25 && strlen($this->reason) <= 255)) {
            $context->buildViolation('The reason for your complaint should be between 25 and 255
            characters long.')
                ->atPath('reason')->addViolation();
        }

        if (!preg_match('/^[A-Z][A-Za-z0-9 ]+\.$/', $this->reason)) {
            $context->buildViolation('The reason for your complaint should start with a capital letter,
            and only contain letters and numbers, and end with a period.')
                ->atPath('reason')->addViolation();
        }
    }
}
