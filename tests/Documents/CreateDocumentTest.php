<?php

namespace OneOffTech\LibrarianClient\Tests\Documents;

use OneOffTech\LibrarianClient\Dto\Document;
use OneOffTech\LibrarianClient\Exceptions\ValidationException;
use OneOffTech\LibrarianClient\Requests\Document\CreateDocumentRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Exceptions\Request\Statuses\UnprocessableEntityException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class CreateDocumentTest extends Base
{
    public function test_document_created(): void
    {
        $mockClient = MockClient::global([
            CreateDocumentRequest::class => MockResponse::fixture('documents-create'),
        ]);

        $connector = $this->connector($mockClient);

        $documentContent = [
            'text' => 'First page text',
            'title' => 'Test document title',
            'metadata' => ['page_number' => 1],
        ];

        $data = new Document('test-document-id', 'en', [$documentContent]);

        $connector->documents('localhost')->create($data);

        $mockClient->assertSent(CreateDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) use ($documentContent) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/documents' &&
                $body['id'] === 'test-document-id' &&
                $body['lang'] === 'en' &&
                ($body['data'][0] ?? false) &&
                empty(array_diff(array_keys($body['data'][0]), array_keys($documentContent)));
        });
    }

    public function test_document_not_created_with_empty_id(): void
    {
        $mockClient = MockClient::global([
            CreateDocumentRequest::class => MockResponse::make(
                body: ['detail' => 'Not found'],
                status: 404,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $documentContent = [
            'text' => 'First page text',
            'title' => 'Test document title',
            'metadata' => ['page_number' => 1],
        ];

        $data = new Document('', 'en', [$documentContent]);

        $this->expectException(ValidationException::class);

        $connector->documents('localhost')->create($data);

        $mockClient->assertNotSent(CreateDocumentRequest::class);
    }

    public function test_document_not_created_with_invalid_text_structure(): void
    {
        $mockClient = MockClient::global([
            CreateDocumentRequest::class => MockResponse::fixture('documents-create-invalid-data'),
        ]);

        $connector = $this->connector($mockClient);

        $documentContent = [
            'text' => 'First page text',
            'title' => 'Test document title',
            'metadata' => ['page_number' => 1],
        ];

        $data = new Document('test-id', 'en', $documentContent);

        $this->expectException(UnprocessableEntityException::class);

        $connector->documents('localhost')->create($data);

        $mockClient->assertSent(CreateDocumentRequest::class);
    }

    public function test_document_created_with_new_format(): void
    {
        $mockClient = MockClient::global([
            CreateDocumentRequest::class => MockResponse::fixture('documents-create-document-model'),
        ]);

        $connector = $this->connector($mockClient);

        $documentContent = [
            'type' => 'doc',
            'content' => [
                [
                    'category' => 'page',
                    'attributes' => [
                        'page' => 1,
                    ],
                    'content' => [
                        [
                            'role' => 'heading',
                            'text' => 'Example Heading',
                            'marks' => [
                                [
                                    'category' => 'textStyle',
                                    'color' => [
                                        'r' => 0,
                                        'b' => 0,
                                        'g' => 0,
                                        'id' => 'color-0',
                                    ],
                                    'font' => [
                                        'name' => 'arialmt',
                                        'id' => 'font-300',
                                        'size' => 26,
                                    ],
                                ],
                            ],
                            'attributes' => [
                                'bounding_box' => [
                                    [
                                        'min_x' => 72.0,
                                        'min_y' => 745.2,
                                        'max_x' => 517.1,
                                        'max_y' => 757.4,
                                        'page' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $data = new Document('test-format-id', 'en', $documentContent);

        $connector->documents('localhost')->create($data);

        $mockClient->assertSent(CreateDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/documents' &&
                $body['id'] === 'test-format-id' &&
                $body['lang'] === 'en' &&
                $body['data']['type'] === 'doc';
        });
    }
}
