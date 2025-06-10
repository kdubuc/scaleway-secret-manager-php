<?php

namespace Kdubuc\ScwSecretManager\Object;

final class SecretEphemeralPolicy extends AbstractObject
{
    public function __construct(
        public string $type = 'disabled',
        public int $duration = 0,
        public ?string $keyId = null,
    ) {
    }
}
