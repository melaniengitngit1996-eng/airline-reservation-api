<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'seats', uniqueConstraints: [
    new ORM\UniqueConstraint(
        name: 'UNIQ_SEAT_FLIGHT_CODE',
        columns: ['flight_id', 'code']
    )
])]
class Seat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Flight $flight = null;

    #[ORM\Column(length: 10)]
    private ?string $code = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, ReservationPassenger>
     */
    #[ORM\OneToMany(targetEntity: ReservationPassenger::class, mappedBy: 'seat')]
    private Collection $reservationPassengers;

    public function __construct()
    {
        $this->reservationPassengers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlight(): ?Flight
    {
        return $this->flight;
    }

    public function setFlight(Flight $flight): static
    {
        $this->flight = $flight;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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

    #[ORM\PrePersist]
    public function setTimestampsOnCreate(): void
    {
        $this->createdAt = new \DateTimeImmutable();
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
            $reservationPassenger->setSeat($this);
        }

        return $this;
    }

    public function removeReservationPassenger(ReservationPassenger $reservationPassenger): static
    {
        if ($this->reservationPassengers->removeElement($reservationPassenger)) {
            // set the owning side to null (unless already changed)
            if ($reservationPassenger->getSeat() === $this) {
                $reservationPassenger->setSeat(null);
            }
        }

        return $this;
    }
}
