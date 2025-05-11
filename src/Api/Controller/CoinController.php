<?php

namespace App\Api\Controller;

use App\Api\Dto\InsertCoinDto;
use App\Application\UseCase\GetTotalInsertedAmountUseCase;
use App\Application\UseCase\InsertCoinUseCase;
use App\Application\UseCase\ReturnCoinsUseCase;
use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CoinController extends BaseController
{
    protected $jsonContext = ['groups' => ['item:read']];

    #[Route('/api/coin', methods: ['POST'], name: 'api_insert_coin', format: 'json')]
    #[OA\Tag(name: 'Actions')]
    #[OA\Response(response: Response::HTTP_OK, description: 'Success')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY,description: 'Input data validation error')]
    #[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR,description: 'Something went wrong')]
    #[OA\Post(summary: 'Insert a coin', description: 'Insert a coin into the vending machine', operationId: 'insertCoin')]
    public function insertCoin(
        #[MapRequestPayload(validationGroups: ['create'])] InsertCoinDto $dto,
        InsertCoinUseCase $insertCoinUseCase,
        ValidatorInterface $validator,
    ): JsonResponse
    {
        $errors = $validator->validate($dto, null, ['Default', 'create']);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credit = $insertCoinUseCase->execute($dto);

        return $this->json(["inserted_amount" => $credit]);
    }

    #[Route('/api/coin/return', methods: ['POST'], name: 'api_return_coin', format: 'json')]
    #[OA\Tag(name: 'Actions')]
    #[OA\Response(response: Response::HTTP_OK, description: 'Success')]
    #[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Something went wrong')]
    #[OA\Post(summary: 'Return coins', description: 'Return all coins inserted into the vending machine',operationId: 'returnCoin')]
    public function returnCoin(ReturnCoinsUseCase $returnCoinsUseCase): JsonResponse
    {
        $returnedCoins = $returnCoinsUseCase->execute();

        return $this->json(
            [
                'message' => $returnedCoins->count().' coins returned successfully',
                'item' => null,
                'coins' => $returnedCoins->toArray(),
                'amount_returned' => $returnedCoins->totalAmount(),
            ],);
    }

    #[Route('/api/coin/total', methods: ['GET'], name: 'api_total_coin', format: 'json')]
    #[OA\Tag(name: 'Info')]
    #[OA\Response(response: Response::HTTP_OK, description: 'Success')]
    #[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Something went wrong')]
    #[OA\Post(summary: 'Get inserted amount', description: 'Get the currently inserted coins and total amount',operationId: 'getCoin')]
    public function viewCoin(GetTotalInsertedAmountUseCase $getTotalInsertedAmountUse): JsonResponse
    {
        $credit = $getTotalInsertedAmountUse->execute();

        return $this->json(["inserted_amount" => $credit]);
    }
}
