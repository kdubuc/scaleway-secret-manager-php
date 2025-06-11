<?php

namespace Kdubuc\ScwSecretManager\Object;

final class AccessSecretVersion extends AbstractObject
{
    /**
     * Access Secret Version constructor.
     *
     * @param string     $secret_id  ID of the secret. (UUID format)
     * @param int        $revision   Version number. The first version of the secret is numbered 1, and all subsequent revisions augment by 1.
     * @param string     $data       the base64-encoded secret payload of the version
     * @param int|null   $data_crc32 The CRC32 checksum of the data as a base-10 integer. This field is only available if a CRC32 was supplied during the creation of the version.
     * @param SecretType $type       type of the secret version
     */
    public function __construct(
        public string $secret_id,
        public int $revision,
        public string $data,
        public ?int $data_crc32,
        public SecretType $type,
    ) {
    }
}
