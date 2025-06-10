<?php

namespace Kdubuc\ScwSecretManager\Object;

interface ObjectInterface extends \JsonSerializable
{
    /**
     * Create an object instance from an array representation.
     */
    public static function fromArray(array $data) : self;
}
