<?php

namespace Kdubuc\ScwSecretManager\Pagerfanta\Adapter;

use Kdubuc\ScwSecretManager\Client;
use Pagerfanta\Adapter\AdapterInterface;

/**
 * Adapter for Scaleway API pagination.
 *
 * @template T
 *
 * @implements AdapterInterface<T>
 */
final class ScwPaginationAdapter implements AdapterInterface
{
    private ?array $current_page = null;

    public function __construct(
        private Client $client,
        private string $method,
        private string $uri,
        private string $scope,
        private array $options = [],
    ) {
    }

    /**
     * Returns the number of results for the list.
     */
    public function getNbResults() : int
    {
        // Retrieving a page (if it does not exist in our internal "cache")
        if (null === $this->current_page) {
            $this->current_page = $this->fetchPage(1, 1);
        }

        // Check if 'total_count' exists in the current page
        $total = $this->current_page['total_count'] ?? -1;
        if (!\is_int($total) || $total < 0) {
            throw new \Exception('The pagination returned by Scaleway is incorrect: total_count is missing or negative');
        }

        return $total;
    }

    /**
     * Returns a slice of the results representing the current page of items in the list.
     */
    public function getSlice(int $offset, int $length) : iterable
    {
        // Calculation of the page number to request corresponding to the requested offset and length
        $page_number = (int) floor((int) ($offset / $length) + 1);

        // Fetch page and store it in the internal "cache"
        $this->current_page = $this->fetchPage($page_number, $length);

        // Check if scope key exists in the current page
        if (!\array_key_exists($this->scope, $this->current_page)) {
            throw new \Exception('The pagination returned by Scaleway is incorrect: scope key "'.$this->scope.'" is missing');
        }

        // Check if the scope is an array
        $data = $this->current_page[$this->scope];
        if (!\is_array($data)) {
            throw new \Exception('The pagination returned by Scaleway is incorrect: scope key "'.$this->scope.'" is not an array');
        }

        return $data;
    }

    /**
     * Récupération de la page.
     */
    private function fetchPage(int $page_number, int $length) : array
    {
        $options = array_merge_recursive($this->options, [
            'query' => [
                'page' => $page_number,
                'page_size' => $length,
            ],
        ]);

        try {
            $response = $this->client->request($this->method, $this->uri, $options);

            if (str_contains($response->getHeaderLine('Content-Type'), 'application/json')) {
                $body = $response->getBody()->__toString();

                $data = (array) json_decode($body, true, 512, \JSON_THROW_ON_ERROR);

                return $data;
            }

            throw new \Exception('The pagination returned by Scaleway is incorrect: Content-Encoding '.$response->getHeaderLine('Content-Type').' invalid');
        } catch (\Exception $e) {
            throw new \Exception('The pagination returned by Scaleway is incorrect: '.$e->getMessage());
        }
    }
}
