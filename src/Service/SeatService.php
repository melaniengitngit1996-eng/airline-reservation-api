<?php

namespace App\Service;

use App\Entity\Flight;
use App\Entity\Seat;
use App\Repository\FlightRepository;
use App\Repository\SeatRepository;
use Doctrine\ORM\EntityManagerInterface;

class SeatService implements SeatServiceInterface {
    public function __construct(
        private FlightRepository $flightRepository,
        private SeatRepository $seatRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function create(int $flightId, array $data): Seat {
        $flight = $this->flightRepository->find($flightId);

        if (!$flight) {
            throw new \DomainException('Flight not found.');
        }

        $seat = new Seat();

        $seat->setCode($data['code']);
        $seat->setFlight($flight);

        $this->entityManager->persist($seat);
        $this->entityManager->flush();

        return $seat;
    }

    public function findAll(): array 
    {
        return $this->seatRepository->findAll();
    }

    public function find(int $flightId, int $id): ?Seat
    {
        return $this->seatRepository->find($id);
    }

    public function update(int $flightId, int $id, array $data): ?Seat
    {
        $flight = $this->flightRepository->find($flightId);

        if (!$flight) {
            throw new \DomainException('Flight not found.');
        }

        $seat = $this->seatRepository->find($id);

        if (!$seat) {
            throw new \DomainException('Seat not found.');
        }

        $seat->setCode($data['code']);
        $seat->setFlight($flight);

        $this->entityManager->persist($seat);
        $this->entityManager->flush();

        return $seat;
    }

    public function delete(int $flightId, int $id): bool
    {
        $flight = $this->flightRepository->find($flightId);

        if (!$flight) {
            throw new \DomainException('Flight not found.');
        }

        $seat = $this->seatRepository->find($id);

        if (!$seat) {
            throw new \DomainException('Seat not found.');
        }

        $this->entityManager->remove($seat);
        $this->entityManager->flush();

        return true;
    }
}