<?php

namespace Kdubuc\ScwSecretManager\Tests\Object;

use PHPUnit\Framework\TestCase;
use Kdubuc\ScwSecretManager\Object\SecretType;
use Kdubuc\ScwSecretManager\Object\AccessSecretVersion;

final class AccessSecretVersionTest extends TestCase
{
    public function testConstructWithAllArguments() : void
    {
        $access = new AccessSecretVersion(
            secret_id: 'sec-123',
            revision: 1,
            data: 'c29tZS1zZWNyZXQtcGF5bG9hZA==',
            data_crc32: 123456789,
            type: SecretType::unknown_type
        );

        $serialized = $access->jsonSerialize();
        $this->assertEquals($access, AccessSecretVersion::fromArray($serialized));
    }
}
