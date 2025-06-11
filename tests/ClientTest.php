<?php

namespace Kdubuc\ScwSecretManager\Tests\Pagerfanta\Adapter;

use PHPUnit\Framework\TestCase;
use Kdubuc\ScwSecretManager\Client;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use Pagerfanta\Adapter\TransformingAdapter;
use Kdubuc\ScwSecretManager\Pagerfanta\Adapter\ScwPaginationAdapter;

final class ClientTest extends TestCase
{
    public function testRequestAddsAuthHeaderAndBuildsUri() : void
    {
        $guzzle   = $this->createMock(GuzzleClient::class);
        $response = $this->createMock(ResponseInterface::class);

        $guzzle->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('GET'),
                $this->equalTo('https://api.scaleway.com/secret-manager/v1beta1/test'),
                $this->callback(function ($options) {
                    return isset($options['headers']['X-Auth-Token']) && 'token' === $options['headers']['X-Auth-Token'];
                })
            )
            ->willReturn($response);

        $client = new Client('token', $guzzle);
        $result = $client->request('GET', 'secret-manager/v1beta1/test');
        $this->assertSame($response, $result);
    }

    public function testRequestRemovesLeadingSlashFromUri() : void
    {
        $guzzle   = $this->createMock(GuzzleClient::class);
        $response = $this->createMock(ResponseInterface::class);

        $guzzle->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('GET'),
                $this->equalTo('https://api.scaleway.com/secret-manager/v1beta1/test'),
                $this->anything()
            )
            ->willReturn($response);

        $client = new Client('token', $guzzle);
        $client->request('GET', '/secret-manager/v1beta1/test');
    }

    public function testRequestMergesHeadersIfPresent() : void
    {
        $guzzle   = $this->createMock(GuzzleClient::class);
        $response = $this->createMock(ResponseInterface::class);

        $guzzle->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('GET'),
                $this->equalTo('https://api.scaleway.com/secret-manager/v1beta1/test'),
                $this->callback(function ($options) {
                    return isset($options['headers']['X-Auth-Token']) && 'token' === $options['headers']['X-Auth-Token']
                        && isset($options['headers']['Custom']) && 'value' === $options['headers']['Custom'];
                })
            )
            ->willReturn($response);

        $client = new Client('token', $guzzle);
        $client->request('GET', 'secret-manager/v1beta1/test', ['headers' => ['Custom' => 'value']]);
    }

    public function testPagination() : void
    {
        $guzzle = $this->createMock(GuzzleClient::class);
        $client = new Client('token', $guzzle);

        $adapter = $client->pagination('GET', 'secret-manager/v1beta1/list', 'versions')->getAdapter();
        $this->assertInstanceOf(ScwPaginationAdapter::class, $adapter);
    }

    public function testPaginationReturnsPagerfantaWithTransformingAdapterWhenModelClassProvided() : void
    {
        $guzzle = $this->createMock(GuzzleClient::class);
        $client = new Client('token', $guzzle);

        $adapter = $client->pagination('GET', 'secret-manager/v1beta1/list', 'versions', \stdClass::class)->getAdapter();
        $this->assertInstanceOf(TransformingAdapter::class, $adapter);
    }
}
