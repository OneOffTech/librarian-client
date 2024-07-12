<?php

namespace OneOffTech\LibrarianClient\Tests\Documents;

use OneOffTech\LibrarianClient\Requests\Document\SummarizeDocumentRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class SummarizeDocumentTest extends Base
{
    public function test_summary_for_document_retrieved(): void
    {
        $mockClient = MockClient::global([
            SummarizeDocumentRequest::class => MockResponse::fixture('summaries-generate'),
        ]);

        $connector = $this->connector($mockClient);

        $summary = $connector->documents('localhost')->summarize('id');

        $this->assertEquals('id', $summary->id);

        $this->assertEquals('en', $summary->language);

        $this->assertEquals('The summary.', $summary->content);

        $mockClient->assertSent(SummarizeDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/documents/id/summary';
        });
    }
}
