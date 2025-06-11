<?php

namespace Kdubuc\ScwSecretManager\Object;

enum VersionStatus : string
{
    /**
     * the version is accessible.
     */
    case enabled = 'enabled';

    /**
     * the version is not accessible but can be enabled.
     */
    case disabled = 'disabled';

    /**
     * the version is scheduled for deletion. It will be deleted in 7 days.
     */
    case scheduled_for_deletion = 'scheduled_for_deletion';

    /**
     * he version is permanently deleted. It is not possible to recover it.
     */
    case deleted = 'deleted';

    /**
     * Unknown status, used when the status is not recognized or not specified.
     */
    case unknown_status = 'unknown_status';
}
