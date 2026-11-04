<?php

namespace App\Service;

use App\Entity\Airport;
use App\Repository\AirportRepository;
use Doctrine\ORM\EntityManagerInterface;

class AirportService implements AirportServiceInterface
{
    public function __construct(
        private AirportRepository $airportRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function create(array $data): Airport
    {
        $existingAirport = $this->airportRepository->findOneBy([
            'code' => $data['code'],
        ]);

        if ($existingAirport) {
            throw new \DomainException('Airport code already exists.');
        }

        $airport = new Airport();

        $airport->setCode($data['code']);
        $airport->setName($data['name']);
        $airport->setCity($data['city']);
        $airport->setCountry($data['country']);

        $this->entityManager->persist($airport);
        $this->entityManager->flush();

        return $airport;
    }

    public function findAll(): array
    {
        return $this->airportRepository->findAll();
    }

    public function find(int $id): ?Airport
    {
        return $this->airportRepository->find($id);
    }

    public function update(int $id, array $data): ?Airport
    {
        $airport = $this->find($id);

        if (!$airport) {
            return null;
        }

        $airport->setCode($data['code']);
        $airport->setName($data['name']);
        $airport->setCity($data['city']);
        $airport->setCountry($data['country']);

        $this->entityManager->flush();

        return $airport;
    }

    public function delete(int $id): bool
    {
        $airport = $this->find($id);

        if (!$airport) {
            return false;
        }

        $this->entityManager->remove($airport);
        $this->entityManager->flush();

        return true;
    }
}