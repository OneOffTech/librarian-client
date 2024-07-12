<?php

namespace OneOffTech\LibrarianClient\Tests\Documents;

use OneOffTech\LibrarianClient\Requests\Document\AllDocumentRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class AllDocumentTest extends Base
{
    public function test_all_documents_listed(): void
    {
        $mockClient = MockClient::global([
            AllDocumentRequest::class => MockResponse::fixture('documents-all'),
        ]);

        $connector = $this->connector($mockClient);

        $documents = $connector->documents('localhost')->all();

        $this->assertEquals([
            '01hs92r9axea64vfp9pf1jya7x',
            '01hs93a4h688vaspzw9yg1yxke',
            '01hg81pwdd626zs0yskxtq3vfm',
        ], $documents->items);

        $mockClient->assertSent(AllDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/documents';
        });
    }

    public function test_empty_libraries_list(): void
    {
        $mockClient = MockClient::global([
            AllDocumentRequest::class => MockResponse::make(
                body: ['results' => []],
                status: 200,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $documents = $connector->documents('localhost')->all();

        $this->assertEquals([], $documents->items);

        $mockClient->assertSent(AllDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/documents';
        });
    }
}
