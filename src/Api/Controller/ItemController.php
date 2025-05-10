<?php

namespace App\Api\Controller;

use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ItemController extends BaseController
{

    #[Route('/api/item',name: 'api_get_items', format: 'json', methods: ['GET'])]
    public function getItems(): JsonResponse
    {
        //todo
    }

    #[Route('/api/item/{id}',name: 'api_get_items', format: 'json', methods: ['POST'])]
    public function getCreateItem(): JsonResponse
    {
        //todo
    }

    #[Route('/api/item/{id}',name: 'api_get_items', format: 'json', methods: ['PATCH'])]
    public function getUpdateItem(int $id): JsonResponse
    {
        //todo
    }
}
