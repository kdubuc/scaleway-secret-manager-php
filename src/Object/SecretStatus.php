<?php

namespace Kdubuc\ScwSecretManager\Object;

enum SecretStatus : string
{
    /**
     * The secret can be read, modified and deleted.
     */
    case ready = 'ready';

    /**
     * No action can be performed on the secret. This status can only be applied and removed by Scaleway.
     */
    case locked = 'locked';

    /**
     * Unknown status, used when the status is not recognized or not specified.
     */
    case unknown_type = 'unknown_status';
}
