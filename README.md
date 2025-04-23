# OneOffTech Librarian Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oneofftech/librarian-client.svg?style=flat-square)](https://packagist.org/packages/oneofftech/librarian-client)
[![Tests](https://github.com/OneOffTech/librarian-client/actions/workflows/run-tests.yml/badge.svg)](https://github.com/OneOffTech/librarian-client/actions/workflows/run-tests.yml)


Allow to interact with OneOffTech Librarian, an AI-enhanced knowledge [search and synthesis](https://oneofftech.xyz/blog/introducing-the-knowledge-management-framework/) agent for your organization's memories.

OneOffTech Librarian provides:

- Enterprise search, retrieve your organization's knowledge via search or chat
- Chat-based question and answer over multiple documents
- Summarization of documents in different languages
- Classification using existing machine learning models
- Extract information in a structured manner using LLMs, usefull for comparative analysis.


> [!IMPORTANT]  
> The Librarian client package is a work in progress. Expect API changes between releases.

## Installation

You can install the package via Composer:

```bash
composer require oneofftech/librarian-client
```

_Requirements_

- PHP 8.2+

## Usage

The `LibrarianConnector` is the main entry point for interacting with the OneOffTech Librarian AI service. 

To create an instance of the `LibrarianConnector`, you need to provide an authentication token and the base URL of the API. OneOffTech Librarian acts on a library, all or a subset of your organization's documents. Some of the features require a library identifier to perform actions on a specific set of documents. The library identifier is provided by OneOffTech during the configuration phase.

```php
use OneOffTech\LibrarianClient\Connectors\LibrarianConnector;

$token = 'your-authentication-token';
$baseUrl = 'your-instance-url';

$connector = new LibrarianConnector($token, $baseUrl);
```

### Managing Documents

**List All Documents in a Library**

```php
$documents = $connector->documents('library-id')->all();

foreach ($documents->items as $document) {
    echo $document->id . ': ' . $document->title . PHP_EOL;
}
```

**Get a Specific Document**

```php
$document = $connector->documents('library-id')->get('document-id');
echo $document->title;
```

**Create a New Document**

```php
use OneOffTech\LibrarianClient\Dto\Document;
use OneOffTech\Parse\Client\DocumentFormat\DocumentNode;

$document = new Document(
    id: 'new-document-id',
    title: 'New Document',
    data: DocumentNode::fromString('Document content')->toArray(),
);

$response = $connector->documents('library-id')->create($document);

echo $response->status();
```

Use our [Parxy](https://github.com/OneOffTech/parse-client) service to get the document content.

**Delete a Document**

```php
$response = $connector->documents('library-id')->delete('document-id');
echo $response->status();
```

### Text and Document Classification

_to be documented_

### Summaries

_to be documented_

### Questions

_to be documented_

### Structured extraction

_to be documented_

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
