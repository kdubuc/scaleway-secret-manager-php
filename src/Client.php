<?php

namespace Kdubuc\ScwSecretManager;

use GuzzleHttp;
use Pagerfanta\Pagerfanta;
use Composer\CaBundle\CaBundle;
use Psr\Http\Message\ResponseInterface;
use Pagerfanta\Adapter\TransformingAdapter;
use Kdubuc\ScwSecretManager\Pagerfanta\Adapter\ScwPaginationAdapter;

final class Client
{
    private GuzzleHttp\Client $client;

    public function __construct(
        private string $scwToken,
        ?GuzzleHttp\Client $client = null,
    ) {
        // Initialize the GuzzleHttpClient if not provided
        if (null === $client) {
            $this->client = new GuzzleHttp\Client([
                'timeout' => 10.0,
                'verify' => CaBundle::getSystemCaRootBundlePath(),
            ]);
        } else {
            $this->client = $client;
        }
    }

    /**
     * Lancement d'une requête vers Metarisc.
     */
    public function request(string $method, string $uri = '', array $options = []) : ResponseInterface
    {
        // Remove leading slash from URI because it can conflict with the base URI
        $uri = 'https://api.scaleway.com/'.ltrim($uri, '/');

        // Authenticate with the Scaleway token provided
        if ($this->scwToken) {
            if (!\array_key_exists('headers', $options) || !\is_array($options['headers'])) {
                $options['headers'] = [];
            }
            $options['headers']['X-Auth-Token'] = $this->scwToken;
        }

        return $this->client->request($method, $uri, $options);
    }

    /**
     * Scaleway paginator.
     *
     * @template T of Object\ObjectInterface
     *
     * @param class-string<Object\ObjectInterface>|null $modelClass class name of the model to unserialize the results into
     *
     * @return Pagerfanta<T>
     */
    public function pagination(
        string $method,
        string $uri,
        string $scope,
        ?string $modelClass = null,
        array $options = [],
    ) : Pagerfanta {
        // If a model class is provided for transformation, wrap the adapter
        if (null !== $modelClass) {
            /** @var ScwPaginationAdapter<array> $adapter2 */
            $adapter2 = new ScwPaginationAdapter($this, $method, $uri, $scope, $options);

            /** @var TransformingAdapter<array, T> $adapter */
            $adapter = new TransformingAdapter($adapter2, static fn ($item) : Object\ObjectInterface => $modelClass::fromArray((array) $item));
        } else {
            /** @var ScwPaginationAdapter<T> $adapter */
            $adapter = new ScwPaginationAdapter($this, $method, $uri, $scope, $options);
        }

        return new Pagerfanta($adapter);
    }
}
