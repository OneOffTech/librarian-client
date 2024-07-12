<?php

namespace OneOffTech\LibrarianClient\Tests\Documents;

use OneOffTech\LibrarianClient\Requests\Document\DeleteDocumentRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class DeleteDocumentTest extends Base
{
    public function test_document_deleted(): void
    {
        $mockClient = MockClient::global([
            DeleteDocumentRequest::class => MockResponse::fixture('documents-delete'),
        ]);

        $connector = $this->connector($mockClient);

        $connector->documents('localhost')->delete('01hg81pwdd626zs0yskxtq3vfm');

        $mockClient->assertSent(DeleteDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/documents/01hg81pwdd626zs0yskxtq3vfm';
        });
    }

    public function test_cannot_delete_non_existing_document(): void
    {
        $mockClient = MockClient::global([
            DeleteDocumentRequest::class => MockResponse::make(
                body: ['detail' => 'Not found'],
                status: 404,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $this->expectException(NotFoundException::class);

        $connector->documents('localhost')->delete('non-existing');

        $mockClient->assertSent(DeleteDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/documents/non-existing';
        });
    }
}
