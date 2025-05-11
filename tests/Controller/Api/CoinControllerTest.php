<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CoinControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient(['environment' => 'test']);
    }

    public function testInsertCoin(): void
    {
        $this->doRequest("/api/coin", '{"coin": 0.05}');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testInsertCoinWithInvalidValue(): void
    {
        $this->doRequest("/api/coin", '{"coin": 0.01}');
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testInsertCoinWithInvalidType(): void
    {
        $this->doRequest("/api/coin", '{"coin": "invalid"}');
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testAvailableAmountIncreasesWhenCoinInserted(): void
    {
        $this->doRequest("/api/coin", '{"coin": 0.05}');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $originalAmount = $response['inserted_amount'];

        $this->doRequest("/api/coin", '{"coin": 0.10}');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($response['inserted_amount'], $originalAmount + 0.10);
    }




   /*public function testUpdateItemQuantity(): void
    {
        $this->doRequest("/api/service/item/1", json_encode(['quantity' => 12,]));
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($content['quantity_available'], 12);
    }*/

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
