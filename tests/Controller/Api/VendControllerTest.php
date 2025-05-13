<?php

namespace App\Tests\Controller\Api;

use App\Application\ViewModel\Vend\VendViewModel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class VendControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient(['environment' => 'test']);
    }

    public function testVendItemWithExactAmount(): void
    {
        $this->prepareInventory(
            ['water' => 1],         // Item name and available quantity
            ['0.05' => 5],          // Change coins available
            [0.25, 0.25, 0.1, 0.05] // Coins inserted (0.65 => the price of water)
        );

        $this->doRequest("/api/vend", '{"item": "water"}');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey(VendViewModel::FIELD_ITEM, $response);
        $this->assertArrayHasKey(VendViewModel::FIELD_MESSAGE, $response);
        $this->assertArrayHasKey(VendViewModel::FIELD_COINS, $response);
        $this->assertEquals( VendViewModel::MESSAGE_ITEM_VENDED, $response[VendViewModel::FIELD_MESSAGE] );
        $this->assertEqualsIgnoringCase('water', $response[VendViewModel::FIELD_ITEM]);
        $this->assertEquals([], $response[VendViewModel::FIELD_COINS]);
    }

    public function testVendItemWithChange(): void
    {
        $this->prepareInventory(
            ['water' => 1],                             // Item name and available quantity
            ["0.25" => 0, "0.1" => 0, "0.05" => 25],    // Change coins available
            [0.25, 0.25, 0.25, 0.1, 0.05]               // Coins inserted (0.90, 0.25 more than price of water)
        );

        $this->doRequest("/api/vend", '{"item": "water"}');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey(VendViewModel::FIELD_ITEM, $response);
        $this->assertArrayHasKey(VendViewModel::FIELD_MESSAGE, $response);
        $this->assertArrayHasKey(VendViewModel::FIELD_COINS, $response);
        $this->assertEquals( VendViewModel::MESSAGE_ITEM_VENDED_WITH_COINS, $response[VendViewModel::FIELD_MESSAGE] );
        $this->assertEqualsIgnoringCase('water', $response[VendViewModel::FIELD_ITEM]);
        $this->assertNotEmpty($response[VendViewModel::FIELD_COINS]);
        $this->assertCount(5, $response[VendViewModel::FIELD_COINS]);
    }

    public function testVendItemWithNotEnoughMoney(): void
    {
        $this->prepareInventory(
            ['water' => 1],
            ['0.05' => 10],
            [0.25, 0.25, 0.1]   // Coins inserted (0.60, 0.05 less than price of water)
        );

        $this->doRequest("/api/vend", '{"item": "water"}');
        $this->assertEquals(Response::HTTP_PAYMENT_REQUIRED, $this->client->getResponse()->getStatusCode());
    }

    public function testVendItemWithNotEnoughChange(): void
    {
        $this->prepareInventory(
            ['water' => 1],
            ['0.25' => 0, '0.1' => 0, '0.05' => 1],
            [0.25, 0.25, 0.25]
        );

        $this->doRequest("/api/vend", '{"item": "water"}');
        $this->assertEquals(Response::HTTP_CONFLICT, $this->client->getResponse()->getStatusCode());
    }

    public function testVendItemNotFound(): void
    {
        $this->prepareInventory(
            ['water' => 1],
            [],
            [0.25, 0.25, 0.25]
        );

        $this->doRequest("/api/vend", '{"item": "this_item_does_not_exist"}');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testVendItemNotAvailable(): void
    {
        $this->prepareInventory(
            ['juice' => 0],
            [],
            [0.25, 0.25, 0.1, 0.05]
        );

        $this->doRequest("/api/vend", '{"item": "juice"}');
        $this->assertEquals(Response::HTTP_SERVICE_UNAVAILABLE, $this->client->getResponse()->getStatusCode());
    }

    private function prepareInventory(array $items = [], array $changeCoins = [], array $coinsInserted = []): void
    {
        foreach ($items as $item => $itemQuantity) {
            $this->doRequest(
                '/api/service/item',
                json_encode(['name' => $item, 'quantity' => (int) $itemQuantity]),
                'PATCH'
            );
        }

        foreach ($changeCoins as $coin => $coinQuantity) {
            $this->doRequest(
                '/api/service/change',
                json_encode(['coin' => (float) $coin, 'quantity' => (int) $coinQuantity]),
                'PUT'
            );
        }

        $this->client->request('POST', '/api/coin/return', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        foreach ($coinsInserted as $coin) {
            $this->doRequest(
                '/api/coin',
                json_encode(['coin' => (float) $coin]),
                'POST'
            );
        }

        $this->client->request('GET', '/api/change', [], [], ['CONTENT_TYPE' => 'application/json']);
        $response = json_decode($this->client->getResponse()->getContent(), true);
    }

    private function doRequest(string $url, string $content, string $requestMethod = 'POST'): void
    {
        $this->client->request(
            $requestMethod,
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );
    }

}
