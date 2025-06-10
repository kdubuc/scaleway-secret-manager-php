<?php

namespace Kdubuc\ScwSecretManager\Object;

enum SecretType : string
{
    /**
     * Default type.
     */
    case opaque = 'opaque';

    /**
     * List of concatenated PEM blocks. They can contain certificates, private keys or any other PEM block types.
     */
    case certificate = 'certificate';

    /**
     * Flat JSON that allows you to set as many first level keys and scalar types as values (string, numeric, boolean) as you need.
     */
    case key_value = 'key_value';

    /**
     * Flat JSON that allows you to set a username and a password.
     */
    case basic_credentials = 'basic_credentials';

    /**
     * Flat JSON that allows you to set an engine, username, password, host, database name, and port.
     */
    case database_credentials = 'database_credentials';

    /**
     * Flat JSON that allows you to set an SSH key.
     */
    case ssh_key = 'ssh_key';

    /**
     * Unknown type, used when the type is not recognized or not specified.
     */
    case unknown_type = 'unknown_type';
}
