<?php

namespace Kdubuc\ScwSecretManager\Tests\Object;

use PHPUnit\Framework\TestCase;
use Kdubuc\ScwSecretManager\Object\Secret;
use Kdubuc\ScwSecretManager\Object\SecretType;
use Kdubuc\ScwSecretManager\Object\SecretStatus;
use Kdubuc\ScwSecretManager\Object\EphemeralPolicyAction;
use Kdubuc\ScwSecretManager\Object\SecretEphemeralPolicy;

final class SecretTest extends TestCase
{
    public function testConstructWithAllArguments() : void
    {
        $ephemeralPolicy = new SecretEphemeralPolicy(
            time_to_live: 3600,
            expires_once_accessed: true,
            action: EphemeralPolicyAction::unknown_action,
        );

        $secret = new Secret(
            id: 'sec-123',
            project_id: 'proj-456',
            name: 'my-secret',
            status: SecretStatus::ready,
            created_at: '2024-01-01T00:00:00Z',
            updated_at: '2024-01-02T00:00:00Z',
            tags: ['foo', 'bar'],
            version_count: 2,
            description: 'desc',
            managed: true,
            protected: true,
            type: SecretType::unknown_type,
            path: '/my/path',
            ephemeral_policy: $ephemeralPolicy,
            used_by: ['service-789'],
            deletion_requested_at: '2024-01-03T00:00:00Z',
            key_id: 'key-789',
            region: 'fr-par'
        );

        $serialized = $secret->jsonSerialize();
        $this->assertEquals($secret, Secret::fromArray($serialized));
    }
}
