<?php

namespace App\Api\Controller;

use App\Api\Dto\VendItemDto;
use App\Application\UseCase\InsertCoinUseCase;
use App\Application\UseCase\ListItemsUseCase;
use App\Application\UseCase\UpdateItemQuantityUseCase;
use App\Application\UseCase\VendItemUseCase;
use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class VendingController extends BaseController
{
    protected $jsonContext = ['groups' => ['item:read']];

    /***************************************
     * Vend an item from the vending machine
     ***************************************/
    #[Route('/api/vend', methods: ['POST'], name: 'api_vend', format: 'json',)]
    #[OA\Response(response: Response::HTTP_OK, description: 'Success')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Input data validation error')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Unkown Item')]
    #[OA\Response(response: Response::HTTP_PAYMENT_REQUIRED, description: 'Not enough money')]
    #[OA\Response(response: Response::HTTP_CONFLICT, description: 'No change available')]
    #[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Something went wrong' )]
    #[OA\Response(response: Response::HTTP_SERVICE_UNAVAILABLE, description: 'Item out of stock')]
    #[OA\Tag( name: 'Actions' )]
    #[OA\Post( description: 'Vend an item from the vending machine', operationId: 'vendItem', summary: 'Get an item', )]
    public function vendItem(
        #[MapRequestPayload(validationGroups: ['Default', 'find'])] VendItemDto $dto,
        VendItemUseCase $vendItemUseCase,
    ): JsonResponse
    {
        $result = $vendItemUseCase->execute($dto);

        return $this->json($result->toArray(), $result->toStatus());
    }
}
