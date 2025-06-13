<?php

namespace Kdubuc\ScwSecretManager\Object;

final class VersionEphemeralProperty extends AbstractObject
{
    /**
     * SecretVersionEphemeralPolicy constructor.
     *
     * @param string|null           $expires_at            The version's expiration date. If not specified, the version does not have an expiration date. (RFC 3339 format)
     * @param bool|null             $expires_once_accessed Returns true if the version expires after a single user access. If not specified, the version can be accessed an unlimited amount of times.
     * @param EphemeralPolicyAction $action                action to perform when the version of a secret expires
     */
    public function __construct(
        public ?string $expires_at = null,
        public ?bool $expires_once_accessed = null,
        public EphemeralPolicyAction $action = EphemeralPolicyAction::unknown_action,
    ) {
    }
}
