<?php

namespace App\Dto\Seat;

use App\Entity\Flight;
use App\Entity\Seat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Seat::class)]
class CreateSeatDto {
    #[Assert\NotBlank]
    #[Map(target: 'code')]
    public string $code;

    #[Map(target: 'flight')]
    public Flight $flight;
    
    public function __construct($flight, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->code = $data['code'] ?? '';

        $this->flight = $flight;
    }
}