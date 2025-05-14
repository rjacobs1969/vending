<?php

namespace App\Api\Controller;

use App\Api\Dto\InsertCoinDto;
use App\Application\UseCase\GetTotalInsertedAmountUseCase;
use App\Application\UseCase\InsertCoinUseCase;
use App\Application\UseCase\ReturnCoinsUseCase;
use App\Application\ViewModel\Vend\VendViewModel;
use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Throwable;

class CoinController extends BaseController
{
    protected $jsonContext = ['groups' => ['item:read']];

    /****************************************
     * Insert a coin into the vending machine
     ****************************************/
    #[Route('/api/coin', methods: ['POST'], name: 'api_insert_coin', format: 'json')]
    #[OA\Tag(name: 'Actions')]
    #[OA\Response(response: Response::HTTP_OK, description: 'Success')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY,description: 'Input data validation error')]
    #[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR,description: 'Something went wrong')]
    #[OA\Post(summary: 'Insert a coin', description: 'Insert a coin into the vending machine', operationId: 'insertCoin')]
    public function insertCoin(
        #[MapRequestPayload(validationGroups:['create'])] InsertCoinDto $dto,
        InsertCoinUseCase $insertCoinUseCase,
    ): JsonResponse
    {
        try {
            $result = $insertCoinUseCase->execute($dto);
            return $this->json($result->toArray(), $result->toStatus());
        } catch (Throwable $e) {
            return $this->fatalErrorResponse($e->getMessage());
        }
    }

    /****************************************************
     * Return all coins inserted into the vending machine
     ****************************************************/
    #[Route('/api/coin/return', methods: ['POST'], name: 'api_return_coin', format: 'json')]
    #[OA\Tag(name: 'Actions')]
    #[OA\Response(response: Response::HTTP_OK, description: 'Success')]
    #[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Something went wrong')]
    #[OA\Post(summary: 'Return coins', description: 'Return all coins inserted into the vending machine',operationId: 'returnCoin')]
    public function returnCoin(ReturnCoinsUseCase $returnCoinsUseCase): JsonResponse
    {
        try {
            $result = $returnCoinsUseCase->execute();
            return $this->json($result->toArray(), $result->toStatus());
        } catch (Throwable $e) {
            return $this->fatalErrorResponse($e->getMessage());
        }
    }

    /******************************************************
     * Get the total amount of the currently inserted coins
     ******************************************************/
    #[Route('/api/coin/total', methods: ['GET'], name: 'api_total_coin', format: 'json')]
    #[OA\Tag(name: 'Info')]
    #[OA\Response(response: Response::HTTP_OK, description: 'Success')]
    #[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Something went wrong')]
    #[OA\Get(summary: 'Get inserted amount', description: 'Get the total amount of the currently inserted coins', operationId: 'getCoin')]
    public function viewCoin(GetTotalInsertedAmountUseCase $getTotalInsertedAmountUseCase): JsonResponse
    {
        try {
            $result = $getTotalInsertedAmountUseCase->execute();
            return $this->json([VendViewModel::FIELD_TOTAL_INSERTED_AMOUNT => $result]);
        } catch (Throwable $e) {
            return $this->fatalErrorResponse($e->getMessage());
        }
    }
}
