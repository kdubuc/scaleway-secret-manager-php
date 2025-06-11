<?php

namespace Kdubuc\ScwSecretManager\Object;

final class Version extends AbstractObject
{
    /**
     * SecretVersion constructor.
     *
     * @param int                             $revision              Version number. The first version of the secret is numbered 1, and all subsequent revisions augment by 1.
     * @param string                          $secret_id             ID of the secret. (UUID format)
     * @param VersionStatus                   $status                current status of the version
     * @param string|null                     $created_at            Date and time of the version's creation. (RFC 3339 format)
     * @param string|null                     $updated_at            Last update of the version. (RFC 3339 format)
     * @param string|null                     $deleted_at            Date and time of the version's deletion. (RFC 3339 format)
     * @param string|null                     $description           description of the version
     * @param bool                            $latest                returns true if the version is the latest
     * @param VersionEphemeralProperty[]|null $ephemeral_properties  Properties of the ephemeral version. Returns the version's expiration date, whether it expires after being accessed once, and the action to perform (disable or delete) once the version expires.
     * @param string|null                     $deletion_requested_at Returns the time at which deletion was requested. (RFC 3339 format)
     */
    public function __construct(
        public int $revision,
        public string $secret_id,
        public VersionStatus $status,
        public ?string $created_at,
        public ?string $updated_at,
        public ?string $deleted_at,
        public ?string $description,
        public bool $latest,
        public ?array $ephemeral_properties = null,
        public ?string $deletion_requested_at = null,
    ) {
    }
}
