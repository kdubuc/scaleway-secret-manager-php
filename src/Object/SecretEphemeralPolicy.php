<?php

namespace Kdubuc\ScwSecretManager\Object;

final class SecretEphemeralPolicy extends AbstractObject
{
    /**
     * SecretEphemeralPolicy constructor.
     *
     * @param string|null           $time_to_live          Time frame, from one second and up to one year, during which the secret's versions are valid. (in seconds)
     * @param bool|null             $expires_once_accessed if true, the version expires after a single user access
     * @param EphemeralPolicyAction $action                action to perform when the version of a secret expires
     */
    public function __construct(
        public ?string $time_to_live = null,
        public ?bool $expires_once_accessed = null,
        public EphemeralPolicyAction $action = EphemeralPolicyAction::unknown_action,
    ) {
    }
}
