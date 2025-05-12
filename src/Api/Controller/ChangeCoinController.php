<?php

namespace App\Api\Controller;

use App\Api\Dto\UpdateChangeCoinQuantityDto;
use App\Api\Dto\UpdateItemQuantityDto;
use App\Application\UseCase\ListChangeCoinUseCase;
use App\Application\UseCase\UpdateChangeCoinQuantityUseCase;
use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Throwable;

class ChangeCoinController extends BaseController
{
    protected $jsonContext = ['groups' => ['item:read']];

    /**************************************************
     * List all inventory of available coins for change
     **************************************************/
    #[Route('/api/change', methods: ['GET'], name: 'api_get_change_coins', format: 'json')]
    #[OA\Response(response: Response::HTTP_OK, description: 'Success')]
    #[OA\Response( response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Something went wrong')]
    #[OA\Tag( name: 'Info' )]
    #[OA\Get( description: 'List of available coins for change', operationId: 'getChangeCoins', summary: 'List change coins')]
    public function getChangeCoins(ListChangeCoinUseCase $listChangeCoinUseCase ): JsonResponse
    {
        $items = $listChangeCoinUseCase->execute();

        return $this->json($items->toArray());
    }

    /***************************
     * Update available quantity
     ***************************/
    #[Route('/api/service/change', name: 'api_update_change', format: 'json', methods: ['PUT'])]
    #[OA\Response( response: Response::HTTP_OK, description: 'Success' )]
    #[OA\Response( response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Data validation error')]
    #[OA\Response( response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Something went wrong')]
    #[OA\Tag( name: 'Service' )]
    #[OA\Put( description: 'Update the number of coins available for change', operationId: 'updateChangeQuantity', summary: 'Update change coins')]
    public function updateItemQuantity(
        #[MapRequestPayload(validationGroups: ['update'])] UpdateChangeCoinQuantityDto $dto,
        UpdateChangeCoinQuantityUseCase $updateChangeCoinQuantityUseCase,
    ): JsonResponse
    {
        try {
            $dto->validate();
            $response = $updateChangeCoinQuantityUseCase->execute($dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable) {
            return $this->json(['error' => 'Something wend wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($response->toArray(), Response::HTTP_OK);
       /* return $item === null
                ? $this->json(['error' => sprintf('Item %s not found', $name)], Response::HTTP_NOT_FOUND)
                : $this->json($item->toArray(), Response::HTTP_OK, [], $this->jsonContext);*/
    }
}
