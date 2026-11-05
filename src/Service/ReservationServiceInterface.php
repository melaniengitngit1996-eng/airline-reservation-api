<?php

namespace App\Service;

use App\Entity\Reservation;

interface ReservationServiceInterface
{
    public function create(array $data): Reservation;

    public function findAll(): array;

    public function find(int $id): ?Reservation;

    public function delete(int $id): bool;
}