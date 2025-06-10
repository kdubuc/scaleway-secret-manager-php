<?php

namespace Kdubuc\ScwSecretManager\Object;

final class SecretVersionEphemeralPolicy extends AbstractObject
{
    public function __construct(
        public string $expires_at = '',
        public bool $expires_once_accessed = false,
        public SecretVersionEphemeralPolicyAction $action = SecretVersionEphemeralPolicyAction::unknown_action,
    ) {
    }
}
