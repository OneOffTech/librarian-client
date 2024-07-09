<?php

namespace OneOffTech\LibrarianClient\Tests\Libraries;

use OneOffTech\LibrarianClient\Dto\Library;
use OneOffTech\LibrarianClient\Dto\LibraryConfiguration;
use OneOffTech\LibrarianClient\Requests\Library\CreateLibraryRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class CreateLibraryTest extends Base
{
    public function test_library_created(): void
    {
        $mockClient = MockClient::global([
            CreateLibraryRequest::class => MockResponse::fixture('libraries-create'),
        ]);

        $connector = $this->connector($mockClient);

        $data = new Library('test', 'Unit Test', new LibraryConfiguration(
            database: [
                'index_fields' => ['resource_id'],
            ],
            text: [
                'n_context_chunk' => 10,
                'chunk_length' => 490,
                'chunk_overlap' => 10,
            ],
        ));

        $connector->libraries()->create($data);

        $mockClient->assertSent(CreateLibraryRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/libraries/' &&
                $body['id'] === 'test' &&
                $body['name'] === 'Unit Test';
        });
    }
}
