<?php

namespace OneOffTech\LibrarianClient\Tests\Documents;

use OneOffTech\LibrarianClient\Requests\Document\GetDocumentRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class GetDocumentTest extends Base
{
    public function test_document_retrieved(): void
    {
        $mockClient = MockClient::global([
            GetDocumentRequest::class => MockResponse::fixture('documents-get'),
        ]);

        $connector = $this->connector($mockClient);

        $document = $connector->documents('localhost')->get('01hs92r9axea64vfp9pf1jya7x');

        $this->assertEquals('01hs92r9axea64vfp9pf1jya7x', $document->id);

        $this->assertEquals('en', $document->language);

        $this->assertCount(2, $document->data);

        $this->assertEquals([
            'text' => 'First page text',
            'title' => null,
            'metadata' => ['page_number' => 1, 'sort_index' => 1],
        ], $document->data[0]);

        $mockClient->assertSent(GetDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/documents/01hs92r9axea64vfp9pf1jya7x';
        });
    }

    public function test_not_existing_document(): void
    {
        $mockClient = MockClient::global([
            GetDocumentRequest::class => MockResponse::make(
                body: ['detail' => 'Not found'],
                status: 404,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $this->expectException(NotFoundException::class);

        $connector->documents('localhost')->get('not-exist');

        $mockClient->assertSent(GetDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/documents/not-exist';
        });
    }
}
