<?php

use App\Entity\Reservation;
use App\Entity\Flight;

it('stores and returns the flight', function () {
    $reservation = new Reservation();
    $flight = new Flight();

    $reservation->setFlight($flight);

    expect($reservation->getFlight())
        ->toBe($flight);
});

it('has null timestamps by default', function () {
    $reservation = new Reservation();

    expect($reservation->getCreatedAt())->toBeNull();
});