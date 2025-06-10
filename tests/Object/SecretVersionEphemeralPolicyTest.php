<?php

namespace Kdubuc\ScwSecretManager\Tests\Object;

use PHPUnit\Framework\TestCase;
use Kdubuc\ScwSecretManager\Object\SecretVersionEphemeralPolicy;
use Kdubuc\ScwSecretManager\Object\SecretVersionEphemeralPolicyAction;

final class SecretVersionEphemeralPolicyTest extends TestCase
{
    public function testConstructWithAllArguments() : void
    {
        $ephemeralPolicy = new SecretVersionEphemeralPolicy(
            expires_at: '2023-10-01T00:00:00Z',
            expires_once_accessed: true,
            action: SecretVersionEphemeralPolicyAction::unknown_action,
        );

        $serialized = $ephemeralPolicy->jsonSerialize();
        $this->assertEquals($ephemeralPolicy, SecretVersionEphemeralPolicy::fromArray($serialized));
    }
}
