<?php

namespace App\Dto\Airport;

use App\Entity\Airport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Airport::class)]
class CreateAirportDto {
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 3)]
    #[Map(target: 'code')]
    public string $code;

    #[Assert\NotBlank]
    #[Map(target: 'name')]
    public string $name;

    #[Assert\NotBlank]
    #[Map(target: 'city')]
    public string $city;

    #[Assert\NotBlank]
    #[Map(target: 'country')]
    public string $country;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        
        $this->code = $data['code'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->city = $data['city'] ?? '';
        $this->country = $data['country'] ?? '';
    }
}