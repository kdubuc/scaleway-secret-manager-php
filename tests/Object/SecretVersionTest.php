<?php

namespace Kdubuc\ScwSecretManager\Tests\Object;

use PHPUnit\Framework\TestCase;
use Kdubuc\ScwSecretManager\Object\SecretVersion;
use Kdubuc\ScwSecretManager\Object\SecretVersionEphemeralPolicy;
use Kdubuc\ScwSecretManager\Object\SecretVersionEphemeralPolicyAction;

final class SecretVersionTest extends TestCase
{
    public function testConstructWithAllArguments() : void
    {
        $version = new SecretVersion(
            revision: 1,
            secret_id: 'sec-123',
            status: 'active',
            created_at: '2024-01-01T00:00:00Z',
            updated_at: '2024-01-02T00:00:00Z',
            deleted_at: null,
            description: 'Version description',
            latest: true,
            ephemeral_properties: [
                new SecretVersionEphemeralPolicy(
                    expires_at: '2024-01-10T00:00:00Z',
                    expires_once_accessed: true,
                    action: SecretVersionEphemeralPolicyAction::unknown_action,
                ),
            ],
            deletion_requested_at: null,
        );

        $serialized = $version->jsonSerialize();
        $this->assertEquals($version, SecretVersion::fromArray($serialized));
    }
}
