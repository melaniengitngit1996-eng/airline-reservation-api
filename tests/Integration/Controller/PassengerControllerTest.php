<?php

use App\Controller\PassengerController;
use App\Entity\Passenger;
use App\Service\PassengerServiceInterface;
use Symfony\Component\HttpFoundation\Request;

it('creates a passenger', function () {
    // Arrange
    $passenger = new Passenger();
    $passenger->setFirstName('Juan');
    $passenger->setLastName('Dela Cruz');
    $passenger->setEmail('juan@example.com');

    $mock = $this->createMock(
        PassengerServiceInterface::class
    );

    $mock
        ->expects($this->once())
        ->method('create')
        ->with([
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'email' => 'juan@example.com',
        ])
        ->willReturn($passenger);

    $controller = new PassengerController($mock);

    // Act
    $request = Request::create(
        '/api/passengers',
        'POST',
        [],
        [],
        [],
        ['CONTENT_TYPE' => 'application/json'],
        json_encode([
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'email' => 'juan@example.com',
        ])
    );

    $response = $controller->create($request);

    // Assert
    expect($response->getStatusCode())->toBe(201);
});