<?php

use App\Entity\Airport;
use App\Repository\AirportRepository;
use App\Service\AirportService;
use Doctrine\ORM\EntityManagerInterface;

it('creates a airport', function () {
    $repository = $this->createStub(AirportRepository::class);
    $entityManager = $this->createMock(EntityManagerInterface::class);

    $entityManager->expects($this->once())
        ->method('persist');

    $entityManager->expects($this->once())
        ->method('flush');

    $service = new AirportService($repository, $entityManager);

    $airport = $service->create([
        'code' => 'CDO',
        'name' => 'Laguindingan Airport',
        'city' => 'Cagayan',
        'country' => 'Philippines'
    ]);

    expect($airport)->toBeInstanceOf(Airport::class);
});

it('gets all airports', function () {
    $airport = new Airport();

    $mock = $this->createMock(AirportRepository::class);

    $mock->expects($this->once())
        ->method('findAll')
        ->willReturn([$airport]);

    $entityManager = $this->createMock(EntityManagerInterface::class);

    $service = new AirportService($mock, $entityManager);

    $airports = $service->findAll();

    expect($airports)->toHaveCount(1);
    expect($airports[0])->toBeInstanceOf(Airport::class);
});

it('get by airport id', function () {
    $airport = new Airport();

    $mock = $this->createMock(AirportRepository::class);

    $mock->expects($this->once())
        ->method('find')
        ->with(1)
        ->willReturn($airport);

    $entityManager = $this->createMock(EntityManagerInterface::class);

    $service = new AirportService($mock, $entityManager);

    $airport = $service->find(1);

    expect($airport)->toBeInstanceOf(Airport::class);
});

it('updates a airport', function () {
    $airport = new Airport();
    $airport->setCode('CDO');
    $airport->setName('Laguindingan Airport');
    $airport->setCity('Cagayan');
    $airport->setCountry('Philippines');

    $repository = $this->createStub(AirportRepository::class);

    $repository->method('find')
        ->willReturn($airport);

    $entityManager = $this->createMock(EntityManagerInterface::class);

    $entityManager->expects($this->once())
        ->method('flush');

    $service = new AirportService($repository, $entityManager);

    $result = $service->update(1, [
        'code' => 'CDL',
        'name' => 'Laguindingan Airport',
        'city' => 'Cagayan',
        'country' => 'Philippines'
    ]);

    expect($result->getCode())->toBe('CDL');
});

it('deletes a airport', function () {
    $airport = new Airport();

    $repository = $this->createStub(AirportRepository::class);

    $repository->method('find')
        ->willReturn($airport);

    $entityManager = $this->createMock(EntityManagerInterface::class);

    $entityManager->expects($this->once())
        ->method('remove')
        ->with($airport);

    $entityManager->expects($this->once())
        ->method('flush');

    $service = new AirportService($repository, $entityManager);

    $result = $service->delete(1);

    expect($result)->toBeTrue();
});