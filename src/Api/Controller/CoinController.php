<?php

namespace App\Api\Controller;

use App\Api\Dto\UpdateCoinQuantityDto;
use App\Application\UseCase\ListItemsUseCase;
use App\Application\UseCase\UpdateItemQuantityUseCase;
use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class CoinController extends BaseController
{
    protected $jsonContext = ['groups' => ['item:read']];

    #[Route('/api/coin',
    name: 'api_insert_coin',
    format: 'json',
    methods: ['POST']
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Success'
    )]
    #[OA\Response(
        response: Response::HTTP_INTERNAL_SERVER_ERROR,
        description: 'Something went wrong'
    )]
    #[OA\Tag(
        name: 'Actions'
    )]
    #[OA\Post(
        description: 'Insert a coin into the vending machine',
        operationId: 'insertCoin',
        summary: 'Insert a coin',
    )]
    public function insertCoin(
        ListItemsUseCase $listItemsUseCase
    ): JsonResponse
    {
        $items = $listItemsUseCase->execute();

        return $this->json($items->toArray());
    }

    #[Route('/api/coin',
        name: 'api_get_coins',
        format: 'json',
        methods: ['GET']
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Success'
    )]
    #[OA\Response(
        response: Response::HTTP_INTERNAL_SERVER_ERROR,
        description: 'Something went wrong'
    )]
    #[OA\Tag(
        name: 'Info'
    )]
    #[OA\Get(
        description: 'List of coins available for change',
        operationId: 'getCoins',
        summary: 'List coins',
    )]
    public function getCoins(
        ListItemsUseCase $listItemsUseCase
    ): JsonResponse
    {
        $items = $listItemsUseCase->execute();

        return $this->json($items->toArray());
    }

    #[Route('/api/service/coin',
        name: 'api_update_coin_quantity',
        format: 'json',
        methods: ['PUT']
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Success'
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Item not found'
    )]
    #[OA\Response(
        response: Response::HTTP_UNPROCESSABLE_ENTITY,
        description: 'Data validation error'
    )]
    #[OA\Response(
        response: Response::HTTP_INTERNAL_SERVER_ERROR,
        description: 'Something went wrong'
    )]
    #[OA\Tag(
        name: 'Service'
    )]
    #[OA\Put(
        description: 'Update coin quantity available for change',
        operationId: 'updateCoinQuantity',
        summary: 'Update coin quantity',
    )]
    public function updateCoinQuantity(
        #[MapRequestPayload(validationGroups: ['updateQuantity'])] UpdateCoinQuantityDto $dto,
        UpdateItemQuantityUseCase $updateItemQuantityUseCase,
        ValidatorInterface $validator,
    ): JsonResponse
    {
        $errors = $validator->validate($dto, null, ['Default', 'updateQuantity']);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
           // $dto->setId($id);
           // $item = $updateItemQuantityUseCase->execute($dto);
        } catch (Throwable) {
            return $this->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json([]);
        //$item === null ?
        //    $this->json(['error' => 'Item with id '.$id.' not found'], Response::HTTP_NOT_FOUND) :
        //    $this->json($item->toArray(), Response::HTTP_OK, [], $this->jsonContext);
    }

}
