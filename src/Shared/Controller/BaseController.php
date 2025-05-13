<?php

namespace App\Shared\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends AbstractController
{
    protected $jsonContext = ['groups' => ['read']];

    public function __construct(
        protected EntityManagerInterface $em,
        protected SerializerInterface $serializer,
    ) {}

    /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     *
     * @param int $status The HTTP status code (200 "OK" by default)
     */
    protected function json(mixed $data, int $status = 200, array $headers = [], ?array $context = null): JsonResponse
    {
        if ($context === null) {
            $context = $this->jsonContext;
        }
        $json = $this->serializer->serialize($data, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], $context));

        return new JsonResponse($json, $status, $headers, true);
    }

    protected function fatalErrorResponse(string $message = 'Something wend wrong'): JsonResponse
    {
        return $this->json(['error' => $message], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
