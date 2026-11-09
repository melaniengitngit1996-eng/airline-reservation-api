<?php

it('has create seat API endpoint', function () {
    $client = $this->createClient();

    $client->jsonRequest('POST', '/api/flights/1/seats', []);

    expect($client->getResponse()->getStatusCode())
        ->not->toBe(404);
});

it('creates seat successfully (201)', function () {
    $client = $this->createClient();

    $client->jsonRequest('POST', '/api/flights/1/seats', [
        'code' => 'A1',
    ]);

    expect($client->getResponse()->getStatusCode())->toBe(201);
    
    $response = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    expect($response['code'])->toBe('A1');
});

it('creates seat in an unknown flight (404)', function () {
    $client = $this->createClient();
    $client->jsonRequest('POST', '/api/flights/7/seats', [
        'code' => 'A2',
    ]);

    expect($client->getResponse()->getStatusCode())
        ->toBe(404);

    $response = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    expect($response['message'])
        ->toBe('Flight not found.');
});

it('creates seat with duplicate seat code in a flight (409)', function () {
    $client = $this->createClient();
    $client->jsonRequest('POST', '/api/flights/1/seats', [
        'code' => 'A1',
    ]);

    expect($client->getResponse()->getStatusCode())
        ->toBe(409);

    $response = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    expect($response['message'])
        ->toBe('The seat code already exists for this flight.');
});

it('creates seat with blank field (400)', function () {
    $client = $this->createClient();
    $client->jsonRequest('POST', '/api/flights/1/seats', [
        'code' => '',
    ]);

    expect($client->getResponse()->getStatusCode())
        ->toBe(400);

    $response = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    expect($response['message'])
        ->toBe('Required fields are missing.');
});

it('creates seat with empty fields (400)', function () {
    $client = $this->createClient();
    $client->jsonRequest('POST', '/api/flights/1/seats', []);

    expect($client->getResponse()->getStatusCode())
        ->toBe(400);

    $response = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    expect($response['message'])
        ->toBe('Required fields are missing.');
});