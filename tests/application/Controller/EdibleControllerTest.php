<?php

declare(strict_types=1);

namespace App\Tests\application\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EdibleControllerTest extends WebTestCase
{
    private const BASE_URL = '/v1/edibles';

    #[DataProvider('listDataProviders')]
    public function testListEdiblesWithFilterAction(int $expectedCount, array $query): void
    {
        $queryString = http_build_query($query);

        $client = static::createClient();
        $client->request(Request::METHOD_GET, self::BASE_URL . '?' . $queryString);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount($expectedCount, $response, 'Unexpected number of edibles returned');
    }

    public function testListEdiblesWithKilogramAsUnitAction(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, self::BASE_URL . '?unit=kg');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(20, $response, 'Expected 20 edibles returned');
        $filteredEdibles = array_filter($response, static fn($edible) => $edible['unit'] === 'kg');
        $this->assertCount(20, $filteredEdibles, 'Expected all edibles to be in kilograms');
    }

    public function testAddAction(): void
    {
        $testName = 'Test Edible';

        $client = static::createClient();
        $client->request(Request::METHOD_POST, self::BASE_URL, [], [], [], json_encode([
            'name' => $testName,
            'type' => 'vegetable',
            'unit' => 'kg',
            'quantity' => 1,
        ]));

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(21, $response, 'Expected 21 edibles returned');
        $filteredEdibles = array_filter($response, static fn($edible) => $edible['name'] === $testName);

        $this->assertCount(1, $filteredEdibles, 'Expected the new edible to be returned');
    }

    public static function listDataProviders(): array
    {
        return [
            'default params' => ['expectedCount' => 20, 'query' => []],
            'fruit type' => ['expectedCount' => 10, 'query' => ['type' => 'fruit']],
            'keyword' => ['expectedCount' => 4, 'query' => ['keyword' => 'be']],
            'min grams' => ['expectedCount' => 5, 'query' => ['minGrams' => 80000]],
            'max grams' => ['expectedCount' => 1, 'query' => ['maxGrams' => 1000]],
            'min grams and fruit type' => ['expectedCount' => 2, 'query' => ['minGrams' => 100000, 'type' => 'fruit']],
            'min grams and max grams' => ['expectedCount' => 3, 'query' => ['minGrams' => 100000, 'maxGrams' => 150000]],
            'max grams and keyword' => ['expectedCount' => 7, 'query' => ['maxGrams' => 20000, 'keyword' => 'e']],
        ];
    }
}