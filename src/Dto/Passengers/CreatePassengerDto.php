<?php

namespace App\Dto\Passengers;

use App\Entity\Passenger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Passenger::class)]
class CreatePassengerDto {
    #[Assert\NotBlank]
    #[Map(target: 'firstName')]
    public string $first_name = '';

    #[Assert\NotBlank]
    #[Map(target: 'lastName')]
    public string $last_name = '';

    #[Assert\NotBlank]
    #[Assert\Email(message: 'Invalid email address.')]
    #[Map(target: 'email')]
    public string $email = '';

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->first_name = $data['first_name'] ?? '';
        $this->last_name = $data['last_name'] ?? '';
        $this->email = $data['email'] ?? '';
    }
}