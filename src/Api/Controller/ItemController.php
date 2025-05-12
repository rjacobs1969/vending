<?php

namespace App\Api\Controller;

use App\Api\Dto\UpdateItemQuantityDto;
use App\Application\UseCase\ListItemsUseCase;
use App\Application\UseCase\UpdateItemQuantityUseCase;
use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Throwable;

class ItemController extends BaseController
{
    protected $jsonContext = ['groups' => ['item:read']];

    /********************************************
     * List all items with price and availability
     ********************************************/
    #[Route('/api/item', methods: ['GET'], name: 'api_get_items', format: 'json')]
    #[OA\Response(response: Response::HTTP_OK, description: 'Success')]
    #[OA\Response( response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Something went wrong')]
    #[OA\Tag( name: 'Info' )]
    #[OA\Get( description: 'List of items with price and availability', operationId: 'getItems', summary: 'List items')]
    public function getItems( ListItemsUseCase $listItemsUseCase ): JsonResponse
    {
        $items = $listItemsUseCase->execute();

        return $this->json($items->toArray());
    }

    /**********************
     * Update item quantity
     **********************/
    #[Route('/api/service/item/{name}', name: 'api_update_quantity', format: 'json', methods: ['PATCH'])]
    #[OA\Response( response: Response::HTTP_OK, description: 'Success' )]
    #[OA\Response( response: Response::HTTP_NOT_FOUND, description: 'Item not found' )]
    #[OA\Response( response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Data validation error')]
    #[OA\Response( response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Something went wrong')]
    #[OA\Tag( name: 'Service' )]
    #[OA\Patch( description: 'Update item quantity', operationId: 'updateItemQuantity', summary: 'Update item quantity')]
    public function updateItemQuantity(
        string $name,
        #[MapRequestPayload(validationGroups: ['updateQuantity'])] UpdateItemQuantityDto $dto,
        UpdateItemQuantityUseCase $updateItemQuantityUseCase,
    ): JsonResponse
    {
        try {
            $dto->setName($name);
            $item = $updateItemQuantityUseCase->execute($dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable) {
            return $this->json(['error' => 'Something wend wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $item === null
                ? $this->json(['error' => sprintf('Item %s not found', $name)], Response::HTTP_NOT_FOUND)
                : $this->json($item->toArray(), Response::HTTP_OK, [], $this->jsonContext);
    }
}
