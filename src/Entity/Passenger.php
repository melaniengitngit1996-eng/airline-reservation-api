<?php

namespace App\Entity;

use App\Repository\PassengerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PassengerRepository::class)]
#[ORM\Table(name: 'passengers')]
#[ORM\HasLifecycleCallbacks]
class Passenger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, ReservationPassenger>
     */
    #[ORM\OneToMany(targetEntity: ReservationPassenger::class, mappedBy: 'passenger')]
    private Collection $reservationPassengers;

    public function __construct()
    {
        $this->reservationPassengers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function setTimestampsOnCreate(): void
    {
        $now = new \DateTimeImmutable();

        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtOnUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * @return Collection<int, ReservationPassenger>
     */
    public function getReservationPassengers(): Collection
    {
        return $this->reservationPassengers;
    }

    public function addReservationPassenger(ReservationPassenger $reservationPassenger): static
    {
        if (!$this->reservationPassengers->contains($reservationPassenger)) {
            $this->reservationPassengers->add($reservationPassenger);
            $reservationPassenger->setPassenger($this);
        }

        return $this;
    }

    public function removeReservationPassenger(ReservationPassenger $reservationPassenger): static
    {
        if ($this->reservationPassengers->removeElement($reservationPassenger)) {
            // set the owning side to null (unless already changed)
            if ($reservationPassenger->getPassenger() === $this) {
                $reservationPassenger->setPassenger(null);
            }
        }

        return $this;
    }
}
