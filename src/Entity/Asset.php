<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity()
 */
class Asset
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="assets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="asset", orphanRemoval=true)
     */
    private $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setAsset($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getAsset() === $this) {
                $ticket->setAsset(null);
            }
        }

        return $this;
    }

    /**
     * @Assert\Callback()
     */
    public function validateName(ExecutionContextInterface $context)
    {
        if (!is_string($this->name)) {
            $context->buildViolation('The name for your asset must be a string.')
                ->atPath('name')->addViolation();
        }

        if (!(strlen($this->name) >= 6 && strlen($this->name) <= 45)) {
            $context->buildViolation('The name for your asset should be between 6 and 45
            characters long.')
                ->atPath('name')->addViolation();
        }

        if (!preg_match('/^[A-Za-z][A-Za-z0-9 ]+$/', $this->name)) {
            $context->buildViolation('The name for your asset should start with a letter (may be capitalized)
             and should only contain standard Latin alphabet and numbers.')
                ->atPath('name')->addViolation();
        }
    }
}
