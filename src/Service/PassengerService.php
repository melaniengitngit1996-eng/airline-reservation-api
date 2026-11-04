<?php

namespace App\Service;

use App\Entity\Passenger;
use App\Repository\PassengerRepository;
use Doctrine\ORM\EntityManagerInterface;

class PassengerService implements PassengerServiceInterface
{
    public function __construct(
        private PassengerRepository $passengerRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function create(array $data): Passenger
    {
        $passenger = new Passenger();

        $passenger->setFirstName($data['first_name']);
        $passenger->setLastName($data['last_name']);
        $passenger->setEmail($data['email']);

        $this->entityManager->persist($passenger);
        $this->entityManager->flush();

        return $passenger;
    }

    public function findAll(): array
    {
        return $this->passengerRepository->findAll();
    }

    public function find(int $id): ?Passenger
    {
        return $this->passengerRepository->find($id);
    }

    public function update(int $id, array $data): ?Passenger
    {
        $passenger = $this->find($id);

        if (!$passenger) {
            return null;
        }

        $passenger->setFirstName($data['first_name']);
        $passenger->setLastName($data['last_name']);
        $passenger->setEmail($data['email']);

        $this->entityManager->flush();

        return $passenger;
    }

    public function delete(int $id): bool
    {
        $passenger = $this->find($id);

        if (!$passenger) {
            return false;
        }

        $this->entityManager->remove($passenger);
        $this->entityManager->flush();

        return true;
    }
}