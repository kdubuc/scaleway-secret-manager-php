<?php

namespace Kdubuc\ScwSecretManager\Tests\Object;

use PHPUnit\Framework\TestCase;
use Kdubuc\ScwSecretManager\Object\EphemeralPolicyAction;
use Kdubuc\ScwSecretManager\Object\VersionEphemeralProperty;

final class VersionEphemeralPropertyTest extends TestCase
{
    public function testConstructWithAllArguments() : void
    {
        $ephemeralPolicy = new VersionEphemeralProperty(
            expires_at: '2023-10-01T00:00:00Z',
            expires_once_accessed: true,
            action: EphemeralPolicyAction::unknown_action,
        );

        $serialized = $ephemeralPolicy->jsonSerialize();
        $this->assertEquals($ephemeralPolicy, VersionEphemeralProperty::fromArray($serialized));
    }
}
