<?php

namespace App\Api\Controller;

use App\Application\UseCase\Item\ListItemsUseCase;
use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Domain\Entity\Item;
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

        return $this->json($items);
    }

   /* #[Route('/api/item/{id}',name: 'api_get_items', format: 'json', methods: ['POST'])]
    public function getCreateItem(): JsonResponse
    {
        //todo
    }

    #[Route('/api/item/{id}',name: 'api_get_items', format: 'json', methods: ['PATCH'])]
    public function getUpdateItem(int $id): JsonResponse
    {
        //todo
    }*/
}
