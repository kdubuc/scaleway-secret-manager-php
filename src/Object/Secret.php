<?php

namespace Kdubuc\ScwSecretManager\Object;

final class Secret extends AbstractObject
{
    /**
     * Secret constructor.
     *
     * @param string                     $id                    ID of the secret. (UUID format)
     * @param string                     $project_id            ID of the Project containing the secret. (UUID format)
     * @param string                     $name                  name of the secret
     * @param SecretStatus               $status                current status of the secret
     * @param string|null                $created_at            Date and time of the secret's creation. (RFC 3339 format)
     * @param string|null                $updated_at            Last update of the secret. (RFC 3339 format)
     * @param string[]                   $tags                  list of the secret's tags
     * @param int                        $version_count         number of versions for this secret
     * @param string|null                $description           description of the secret
     * @param bool                       $managed               returns true for secrets that are managed by another product
     * @param bool                       $protected             returns true for protected secrets that cannot be deleted
     * @param SecretType                 $type                  type of the secret
     * @param string                     $path                  Path of the secret. Location of the secret in the directory structure.
     * @param SecretEphemeralPolicy|null $ephemeral_policy      policy that defines whether/when a secret's versions expire
     * @param string[]                   $used_by               list of Scaleway resources that can access and manage the secret
     * @param string|null                $deletion_requested_at Returns the time at which deletion was requested. (RFC 3339 format)
     * @param string|null                $key_id                The Scaleway Key Manager key ID used to encrypt and decrypt secret versions. (UUID format)
     * @param string                     $region                region of the secret
     */
    public function __construct(
        public string $id,
        public string $project_id,
        public string $name,
        public SecretStatus $status,
        public ?string $created_at,
        public ?string $updated_at,
        public array $tags,
        public int $version_count,
        public ?string $description,
        public bool $managed,
        public bool $protected,
        public SecretType $type,
        public string $path,
        public ?SecretEphemeralPolicy $ephemeral_policy,
        public array $used_by,
        public ?string $deletion_requested_at,
        public ?string $key_id,
        public string $region,
    ) {
    }
}
