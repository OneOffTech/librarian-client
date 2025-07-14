<?php

namespace OneOffTech\LibrarianClient\Tests\Embeddings;

use OneOffTech\LibrarianClient\Dto\Document;
use OneOffTech\LibrarianClient\Requests\Embeddings\EmbeddingsRequest;
use OneOffTech\LibrarianClient\Requests\StructuredExtraction\ExtractRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class EmbeddingsTest extends Base
{
    public function test_embeddings_computed(): void
    {
        $mockClient = MockClient::global([
            EmbeddingsRequest::class => MockResponse::fixture('embeddings'),
        ]);

        $connector = $this->connector($mockClient);

        $embeddings = $connector->embeddings(['Test to compute the embedding']);

        $mockClient->assertSent(EmbeddingsRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/embeddings' &&
                $body['corpus'] === ['Test to compute the embedding'];
        });

        $this->assertCount(1, $embeddings->vectors);

    }

}
