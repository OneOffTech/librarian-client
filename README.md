# OneOffTech Librarian Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oneofftech/librarian-client.svg?style=flat-square)](https://packagist.org/packages/oneofftech/librarian-client)
[![Tests](https://img.shields.io/github/actions/workflow/status/oneofftech/librarian-client/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/oneofftech/librarian-client/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/oneofftech/librarian-client.svg?style=flat-square)](https://packagist.org/packages/oneofftech/librarian-client)

OneOffTech Librarian client allow to interact with OneOffTech Artificial Intelligence stack.

The AI stack offers opinionated tools for Digital Libraries and [Knowledge Management](https://oneofftech.xyz/blog/introducing-the-knowledge-management-framework/) within organizations. 


> [!IMPORTANT]  
> The Librarian client package is under heavy development and is not ready for production use.

## Installation

You can install the package via Composer:

```bash
composer require oneofftech/librarian-client
```

_Requirements_

- PHP 8.2+

## Usage

```php
$skeleton = new OneOffTech\LibrarianClient();
echo $skeleton->echoPhrase('Hello, OneOffTech!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to the Librarian client! The contribution guide can be found in the [CONTRIBUTING.md](./.github/CONTRIBUTING.md) file.

## Security Vulnerabilities

Please review [our security policy](./.github/SECURITY.md) on how to report security vulnerabilities.

## Supporters

The project is provided and supported by [OneOff-Tech (UG)](https://oneofftech.de).

<p align="left"><a href="https://oneofftech.de" target="_blank"><img src="https://raw.githubusercontent.com/OneOffTech/.github/main/art/oneofftech-logo.svg" width="200"></a></p>


## Credits

- [Alessio Vertemati](https://github.com/avvertix)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
