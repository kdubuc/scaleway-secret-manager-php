<?php

namespace Kdubuc\ScwSecretManager\Tests\Object;

use PHPUnit\Framework\TestCase;
use Kdubuc\ScwSecretManager\Object\SecretEphemeralPolicy;

final class SecretEphemeralPolicyTest extends TestCase
{
    public function testConstructWithAllArguments() : void
    {
        $ephemeralPolicy = new SecretEphemeralPolicy(
            type: 'disabled',
            duration: 0,
            keyId: null
        );

        $serialized = $ephemeralPolicy->jsonSerialize();
        $this->assertEquals($ephemeralPolicy, SecretEphemeralPolicy::fromArray($serialized));
    }
}
