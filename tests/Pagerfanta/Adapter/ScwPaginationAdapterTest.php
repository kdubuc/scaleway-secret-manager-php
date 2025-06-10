<?php

namespace Kdubuc\ScwSecretManager\Tests\Pagerfanta\Adapter;

use PHPUnit\Framework\TestCase;
use Kdubuc\ScwSecretManager\Client;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use Kdubuc\ScwSecretManager\Pagerfanta\Adapter\ScwPaginationAdapter;

final class ScwPaginationAdapterTest extends TestCase
{
    public function testPagination() : void
    {
        $data = [
            'total_count' => 3,
            'items' => [
                ['id' => '1', 'name' => 'Item 1'],
                ['id' => '2', 'name' => 'Item 2'],
                ['id' => '3', 'name' => 'Item 3'],
            ],
        ];

        $client = $this->createMock(Client::class);
        $client->method('request')->with(
            $this->equalTo('GET'),
            $this->equalTo('/uri'),
            $this->equalTo([
                'query' => [
                    'page' => 1,
                    'page_size' => 3,
                ],
            ]),
        )->willReturn($this->createResponseMock($data));

        $adapter = new ScwPaginationAdapter($client, 'GET', '/uri', 'items');
        $this->assertSame($data['items'], $adapter->getSlice(1, 3));
        $this->assertSame($data['total_count'], $adapter->getNbResults());
    }

    private function createResponseMock(array $data, string $contentType = 'application/json')
    {
        $body = $this->createMock(StreamInterface::class);
        $body->method('__toString')->willReturn(json_encode($data));

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getHeaderLine')->with('Content-Type')->willReturn($contentType);
        $response->method('getBody')->willReturn($body);

        return $response;
    }
}
