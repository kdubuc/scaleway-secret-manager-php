<?php

namespace Kdubuc\ScwSecretManager\Object;

enum SecretVersionEphemeralPolicyAction : string
{
    /**
     * Default type.
     */
    case unknown_action = 'unknown_action';

    /**
     * The version is deleted once it expires.
     */
    case delete = 'delete';

    /**
     * The version is disabled once it expires.
     */
    case disable = 'disable';
}
