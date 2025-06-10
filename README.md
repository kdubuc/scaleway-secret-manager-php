# Scaleway Secret Manager PHP Client

PHP client for [Scaleway Secret Manager](https://www.scaleway.com/en/developers/api/secret-manager/).

## Installation

You can install the package via Composer:

```bash
composer require kdubuc/scaleway-secret-manager
```

## Usage

```php
require 'vendor/autoload.php';
use Scaleway\SecretManager\Client;
$client = new Client([]);
```

## Testing

``` bash
$ composer run code:tests
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email kevindubuc62@gmail.com instead of using the issue tracker.

## Credits

- [Kévin DUBUC](https://github.com/kdubuc)
- [All Contributors](https://github.com/kdubuc/scaleway-secret-manager-php/graphs/contributors)

## License

The CeCILL-B License. Please see [License File](LICENSE.md) for more information.