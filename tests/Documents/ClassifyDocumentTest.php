<?php

namespace OneOffTech\LibrarianClient\Tests\Documents;

use OneOffTech\LibrarianClient\Requests\Classifier\ClassifyDocumentRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class ClassifyDocumentTest extends Base
{
    public function test_document_classified_via_document_resource(): void
    {
        $mockClient = MockClient::global([
            ClassifyDocumentRequest::class => MockResponse::fixture('classifier-text'),
        ]);

        $connector = $this->connector($mockClient);

        $classification = $connector->classifiers('localhost')->classifyDocument('test-classifier-id', 'text-id');

        $this->assertEquals('text-id', $classification->id);

        $this->assertEquals('test-classifier-id', $classification->classifier);

        $this->assertCount(16, $classification->results->items);

        $this->assertEquals([
            'name' => 'sdg9',
            'score' => 0.15923157,
            'n_matches' => 1,
            'matches' => [
                0 => [
                    'text' => 'This is the content to be classified.',
                    'score' => 0.15923157,
                    'match_position' => [
                        'length' => 37,
                        'page' => 1,
                    ],
                ],
            ],
        ], $classification->results->items[0]);

        $mockClient->assertSent(ClassifyDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/documents/text-id/classify' &&
                $body['classifier'] === 'test-classifier-id';
        });
    }
}
