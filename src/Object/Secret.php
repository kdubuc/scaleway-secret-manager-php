<?php

namespace Kdubuc\ScwSecretManager\Object;

final class Secret extends AbstractObject
{
    public function __construct(
        public string $id,
        public string $project_id,
        public string $name,
        public string $status,
        public string $created_at,
        public string $updated_at,
        public array $tags = [],
        public int $version_count = 0,
        public string $description = '',
        public bool $managed = false,
        public bool $protected = false,
        public SecretType $type = SecretType::unknown_type,
        public string $path = '/',
        public ?SecretEphemeralPolicy $ephemeral_policy = null,
        public array $used_by = [],
        public ?string $deletion_requested_at = null,
        public ?string $key_id = null,
        public ?string $region = null,
    ) {
    }
}
