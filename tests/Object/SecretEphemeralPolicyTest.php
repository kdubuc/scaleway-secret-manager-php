<?php

namespace Kdubuc\ScwSecretManager\Tests\Object;

use PHPUnit\Framework\TestCase;
use Kdubuc\ScwSecretManager\Object\EphemeralPolicyAction;
use Kdubuc\ScwSecretManager\Object\SecretEphemeralPolicy;

final class SecretEphemeralPolicyTest extends TestCase
{
    public function testConstructWithAllArguments() : void
    {
        $ephemeralPolicy = new SecretEphemeralPolicy(
            time_to_live: 3600,
            expires_once_accessed: true,
            action: EphemeralPolicyAction::unknown_action,
        );

        $serialized = $ephemeralPolicy->jsonSerialize();
        $this->assertEquals($ephemeralPolicy, SecretEphemeralPolicy::fromArray($serialized));
    }
}
