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
     * Retrieve the list of secrets created within an Organization and/or Project. You must specify either the organization_id or the project_id and the region.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secrets-list-secrets
     *
     * @param string $region the region where the secrets are stored
     * @param array  $params optional parameters to filter the list of secrets
     *
     * @return Pagerfanta<Object\Secret>
     */
    public function listSecrets(
        string $region,
        array $params = [],
    ) : Pagerfanta {
        return $this->pagination('GET', "secret-manager/v1beta1/regions/{$region}/secrets", 'secrets', Object\Secret::class, [
            'query' => $params,
        ]);
    }

    /**
     * Create a secret in a given region specified by the region parameter.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secrets-create-a-secret
     *
     * @psalm-type CreateSecretBody = array{project_id?:string,name?:string,tags?:string[],description?:string|null,type?:string,path?:string|null,ephemeral_policy?:array{time_to_live?:string|null,expires_once_accessed?:bool|null,action?:string},protected?:bool|null,key_id?:string|null}
     *
     * @param string           $region The region you want to target
     * @param CreateSecretBody $body   secret data to create
     */
    public function createSecret(
        string $region,
        array $body,
    ) : Object\Secret {
        $response = $this->request('POST', "secret-manager/v1beta1/regions/{$region}/secrets", [
            'json' => $body,
        ]);

        return Object\Secret::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Retrieve the metadata of a secret specified by the region and secret_id parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secrets-get-metadata-using-the-secrets-id
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret. (UUID format)
     */
    public function getSecretMetadata(
        string $region,
        string $secret_id,
    ) : Object\Secret {
        $response = $this->request('GET', "/secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}");

        return Object\Secret::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Edit a secret's metadata such as name, tag(s), description and ephemeral policy.
     * The secret to update is specified by the secret_id and region parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secrets-update-metadata-of-a-secret
     *
     * @psalm-type UpdateSecretMetadataBody = array{name?:string,tags?:string[],description?:string|null,path?:string|null,ephemeral_policy?:array{time_to_live?:string|null,expires_once_accessed?:bool|null,action?:string}}
     *
     * @param string                   $region    The region you want to target
     * @param string                   $secret_id ID of the secret. (UUID format)
     * @param UpdateSecretMetadataBody $body      secret metadata to update
     */
    public function updateSecretMetadata(
        string $region,
        string $secret_id,
        array $body,
    ) : Object\Secret {
        $response = $this->request('PATCH', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}", [
            'json' => $body,
        ]);

        return Object\Secret::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Delete a given secret specified by the region and secret_id parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secrets-delete-a-secret
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret. (UUID format)
     */
    public function deleteSecret(
        string $region,
        string $secret_id,
    ) : void {
        $this->request('DELETE', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}");
    }

    /**
     * Allow a product to use the secret.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secrets-allow-a-product-to-use-the-secret
     *
     * @param string                            $region     The region you want to target
     * @param string                            $secret_id  ID of the secret. (UUID format)
     * @param 'unknown_product'|'edge_services' $product_id ID of the product to add
     */
    public function allowProduct(
        string $region,
        string $secret_id,
        string $product_id,
    ) : void {
        $this->request('POST', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/add-owner", [
            'json' => [
                'product_id' => $product_id,
            ],
        ]);
    }

    /**
     * Enable secret protection for a given secret specified by the secret_id parameter.
     * Enabling secret protection means that your secret can be read and modified, but it cannot be deleted.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secrets-enable-secret-protection
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret to enable secret protection for. (UUID format)
     */
    public function enableSecretProtection(
        string $region,
        string $secret_id,
    ) : Object\Secret {
        $response = $this->request('POST', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/protect");

        return Object\Secret::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Restore a secret and all its versions scheduled for deletion specified by the region and secret_id parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secrets-restore-a-secret
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret to restore. (UUID format)
     */
    public function restoreSecret(
        string $region,
        string $secret_id,
    ) : Object\Secret {
        $response = $this->request('POST', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/restore");

        return Object\Secret::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Disable secret protection for a given secret specified by the secret_id parameter.
     * Disabling secret protection means that your secret can be read, modified and deleted.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secrets-disable-secret-protection
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret to disable secret protection for. (UUID format)
     */
    public function disableSecretProtection(
        string $region,
        string $secret_id,
    ) : Object\Secret {
        $response = $this->request('POST', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/unprotect");

        return Object\Secret::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Restore a secret's version specified by the region, secret_id and revision parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secrets-restore-a-version
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret. (UUID format)
     * @param string $revision  Version number
     */
    public function restoreVersion(
        string $region,
        string $secret_id,
        string $revision,
    ) : Object\Version {
        $response = $this->request('POST', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/versions/{$revision}/restore");

        return Object\Version::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Access sensitive data in a secret's version specified by the region, secret_name, secret_path and revision parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secret-versions-access-a-secrets-version-using-the-secrets-name-and-path
     *
     * @param string $region      The region you want to target
     * @param string $revision    value can be either: an integer (the revision number), "latest" (the latest revision), "latest_enabled" (the latest enabled revision)
     * @param string $secret_path secret's path
     * @param string $secret_name secret's name
     * @param string $project_id  ID of the Project to target. (UUID format)
     */
    public function accessSecretVersion(
        string $region,
        string $revision,
        string $secret_path,
        string $secret_name,
        string $project_id,
    ) : Object\AccessSecretVersion {
        $response = $this->request('GET', "https://api.scaleway.com/secret-manager/v1beta1/regions/{$region}/secrets-by-path/versions/{$revision}/access", [
            'query' => [
                'secret_path' => $secret_path,
                'secret_name' => $secret_name,
                'project_id' => $project_id,
            ],
        ]);

        return Object\AccessSecretVersion::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Retrieve the list of a given secret's versions specified by the secret_id and region parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secret-versions-list-versions-of-a-secret-using-the-secrets-id
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret. (UUID format)
     * @param array  $params    optional parameters to filter the list of versions
     *
     * @return Pagerfanta<Object\Version>
     */
    public function listSecretVersions(
        string $region,
        string $secret_id,
        array $params = [],
    ) : Pagerfanta {
        return $this->pagination('GET', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/versions", 'versions', Object\Version::class, [
            'query' => $params,
        ]);
    }

    /**
     * Create a version of a given secret specified by the region and secret_id parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secret-versions-create-a-version
     *
     * @psalm-type CreateVersionBody = array{data?:string,description?:string|null,disable_previous?:bool|null,data_crc32?:int|null}
     *
     * @param string            $region    The region you want to target
     * @param string            $secret_id ID of the secret. (UUID format)
     * @param CreateVersionBody $body      version data to create
     */
    public function createVersion(
        string $region,
        string $secret_id,
        array $body,
    ) : Object\Version {
        $response = $this->request('POST', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/versions", [
            'json' => $body,
        ]);

        return Object\Version::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Retrieve the metadata of a secret's given version specified by the region, secret_id and revision parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secret-versions-get-metadata-of-a-secrets-version-using-the-secrets-id
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret. (UUID format)
     * @param string $revision  value can be either: an integer (the revision number), "latest" (the latest revision), "latest_enabled" (the latest enabled revision)
     */
    public function getVersionMetadata(
        string $region,
        string $secret_id,
        string $revision,
    ) : Object\Version {
        $response = $this->request('GET', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/versions/{$revision}");

        return Object\Version::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Edit the metadata of a secret's given version, specified by the region, secret_id and revision parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secret-versions-update-metadata-of-a-version
     *
     * @psalm-type UpdateVersionMetadataBody = array{description?:string|null,ephemeral_properties?:array{expires_at?:string|null,expires_once_accessed?:bool|null,action?:string}}
     *
     * @param string                    $region    The region you want to target
     * @param string                    $secret_id ID of the secret. (UUID format)
     * @param string                    $revision  value can be either: an integer (the revision number), "latest" (the latest revision), "latest_enabled" (the latest enabled revision)
     * @param UpdateVersionMetadataBody $body      version metadata to update
     */
    public function updateVersionMetadata(
        string $region,
        string $secret_id,
        string $revision,
        array $body = [],
    ) : Object\Version {
        $response = $this->request('PATCH', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/versions/{$revision}", [
            'json' => $body,
        ]);

        return Object\Version::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Delete a secret's version and the sensitive data contained in it. Deleting a version is permanent and cannot be undone.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secret-versions-delete-a-version
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret. (UUID format)
     * @param string $revision  value can be either: an integer (the revision number), "latest" (the latest revision), "latest_enabled" (the latest enabled revision)
     */
    public function deleteVersion(
        string $region,
        string $secret_id,
        string $revision,
    ) : void {
        $this->request('DELETE', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/versions/{$revision}");
    }

    /**
     * Access sensitive data in a secret's version specified by the region, secret_id and revision parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secret-versions-access-a-secrets-version-using-the-secrets-id
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret. (UUID format)
     * @param string $revision  value can be either: an integer (the revision number), "latest" (the latest revision), "latest_enabled" (the latest enabled revision)
     */
    public function accessVersion(
        string $region,
        string $secret_id,
        string $revision,
    ) : Object\AccessSecretVersion {
        $response = $this->request('GET', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/versions/{$revision}/access");

        return Object\AccessSecretVersion::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Make a specific version inaccessible. You must specify the region, secret_id and revision parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secret-versions-disable-a-version
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret. (UUID format)
     * @param string $revision  value can be either: an integer (the revision number), "latest" (the latest revision), "latest_enabled" (the latest enabled revision)
     */
    public function disableVersion(
        string $region,
        string $secret_id,
        string $revision,
    ) : Object\Version {
        $response = $this->request('POST', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/versions/{$revision}/disable");

        return Object\Version::fromArray((array) json_decode($response->getBody()->getContents(), true));
    }

    /**
     * Make a specific version accessible. You must specify the region, secret_id and revision parameters.
     *
     * https://www.scaleway.com/en/developers/api/secret-manager/#path-secret-versions-enable-a-version
     *
     * @param string $region    The region you want to target
     * @param string $secret_id ID of the secret. (UUID format)
     * @param string $revision  value can be either: an integer (the revision number), "latest" (the latest revision), "latest_enabled" (the latest enabled revision)
     */
    public function enableVersion(
        string $region,
        string $secret_id,
        string $revision,
    ) : Object\Version {
        $response = $this->request('POST', "secret-manager/v1beta1/regions/{$region}/secrets/{$secret_id}/versions/{$revision}/enable");

        return Object\Version::fromArray((array) json_decode($response->getBody()->getContents(), true));
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
