<?php

namespace Kdubuc\ScwSecretManager\Tests\Object;

use PHPUnit\Framework\TestCase;
use Kdubuc\ScwSecretManager\Object\Version;
use Kdubuc\ScwSecretManager\Object\VersionStatus;
use Kdubuc\ScwSecretManager\Object\EphemeralPolicyAction;
use Kdubuc\ScwSecretManager\Object\VersionEphemeralProperty;

final class SecretVersionTest extends TestCase
{
    public function testConstructWithAllArguments() : void
    {
        $version = new Version(
            revision: 1,
            secret_id: 'sec-123',
            status: VersionStatus::unknown_status,
            created_at: '2024-01-01T00:00:00Z',
            updated_at: '2024-01-02T00:00:00Z',
            deleted_at: null,
            description: 'Version description',
            latest: true,
            ephemeral_properties: [
                new VersionEphemeralProperty(
                    expires_at: '2024-01-10T00:00:00Z',
                    expires_once_accessed: true,
                    action: EphemeralPolicyAction::unknown_action,
                ),
            ],
            deletion_requested_at: null,
        );

        $serialized = $version->jsonSerialize();
        $this->assertEquals($version, Version::fromArray($serialized));
    }
}
