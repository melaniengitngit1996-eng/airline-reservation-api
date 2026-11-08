<?php

namespace App\Controller\Seat;

use App\Dto\Seat\CreateSeatDto;
use App\Entity\Seat;
use App\Repository\FlightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/flights/{flightId}/seats')]
class CreateSeatController {
    private FlightRepository $flightRepository;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private ObjectMapperInterface $objectMapper;

    public function __construct(
        FlightRepository $flightRepository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        ObjectMapperInterface $objectMapper
    )
    {
        $this->flightRepository = $flightRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->objectMapper = $objectMapper;
    }

    #[Route('', methods: ['POST'])]
    public function __invoke($flightId, Request $request)
    {
        $flight = $this->flightRepository->find($flightId);

        if (!$flight) {
            return new JsonResponse([
                'message' => 'Flight not found.'
            ], 404);
        }

        $dto = new CreateSeatDto($flight, $request);

        $errors = $this->validator->validate($dto);
        
        if (count($errors) > 0) {
            return new JsonResponse([
                'message' => 'Required fields are missing.',
                'errors' => (string) $errors,
            ], 400);
        }

        $seat = $this->objectMapper->map($dto, Seat::class);

        $errors = $this->validator->validate($seat);

        if (count($errors) > 0) {
            return new JsonResponse([
                'message' => $errors[0]->getMessage(),
                'errors' => (string) $errors,
            ], 409);
        }

        $this->entityManager->persist($seat);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $seat->getId(),
            'flight_id' => $seat->getFlight()->getId(),
            'code' => $seat->getCode(),
            'taken' => true
        ], 201);
    }
}