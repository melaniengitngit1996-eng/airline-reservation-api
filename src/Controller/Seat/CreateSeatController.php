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

/**
 * Controller for creating seats associated with a specific flight.
 */
#[Route('/api/flights/{flightId}/seats')]
class CreateSeatController
{
    /**
     * Repository for retrieving flight records.
     */
    private FlightRepository $flightRepository;

    /**
     * Entity manager for persisting the seat entity.
     */
    private EntityManagerInterface $entityManager;

    /**
     * Validator for validating DTOs and entities.
     */
    private ValidatorInterface $validator;

    /**
     * Object mapper for mapping DTOs to entities.
     */
    private ObjectMapperInterface $objectMapper;

    /**
     * Creates a new CreateSeatController instance.
     *
     * @param FlightRepository $flightRepository Repository for retrieving flights.
     * @param EntityManagerInterface $entityManager Entity manager for database operations.
     * @param ValidatorInterface $validator Validator for DTO and entity validation.
     * @param ObjectMapperInterface $objectMapper Object mapper for DTO-to-entity mapping.
     */
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

    /**
     * Creates a new seat for the specified flight.
     *
     * @param int|string $flightId The ID of the flight associated with the seat.
     * @param Request $request The HTTP request containing the seat data.
     *
     * @return JsonResponse The created seat or an error response.
     */
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