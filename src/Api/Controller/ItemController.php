<?php

namespace App\Api\Controller;

use App\Api\Dto\UpdateItemQuantityDto;
use App\Application\UseCase\Item\ListItemsUseCase;
use App\Application\UseCase\Item\UpdateItemQuantityUseCase;
use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Domain\Entity\Item;
use Doctrine\ORM\Query\AST\UpdateItem;
use OpenApi\Attributes as OA;

class ItemController extends BaseController
{
    protected $jsonContext = ['groups' => ['item:read']];

    #[Route('/api/item',
        name: 'api_get_items',
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
        description: 'List of items with price and availability',
        operationId: 'getItems',
        summary: 'List items',
    )]
    public function getItems(
        ListItemsUseCase $listItemsUseCase
    ): JsonResponse
    {
        $items = $listItemsUseCase->execute();

        return $this->json($items->toArray());
    }

    #[Route('/api/service/item/{id}',
        name: 'api_update_quantity',
        format: 'json',
        methods: ['PATCH']
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
        name: 'Service'
    )]
    #[OA\Patch(
        description: 'Update item quantity',
        operationId: 'updateItemQuantity',
        summary: 'Update item quantity',
    )]
    public function updateItemQuantity(
        int $id,
        #[MapRequestPayload(validationGroups: ['updateQuantity'])] UpdateItemQuantityDto $dto,
        UpdateItemQuantityUseCase $updateItemQuantityUseCase
    ): JsonResponse
    {
        $dto->setId($id);
        $item = $updateItemQuantityUseCase->execute($dto);

        return $this->json($item->toArray(), Response::HTTP_OK, [], $this->jsonContext);
    }

}
