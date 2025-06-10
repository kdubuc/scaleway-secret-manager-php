<?php

namespace Kdubuc\ScwSecretManager\Object;

final class SecretVersion extends AbstractObject
{
    /**
     * @param SecretVersionEphemeralPolicy[]|null $ephemeral_properties
     */
    public function __construct(
        public int $revision,
        public string $secret_id,
        public string $status,
        public string $created_at,
        public string $updated_at,
        public ?string $deleted_at = null,
        public string $description = '',
        public bool $latest = false,
        public ?array $ephemeral_properties = null,
        public ?string $deletion_requested_at = null,
    ) {
    }
}
