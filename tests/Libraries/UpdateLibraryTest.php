<?php

namespace OneOffTech\LibrarianClient\Tests\Libraries;

use OneOffTech\LibrarianClient\Dto\LibraryConfiguration;
use OneOffTech\LibrarianClient\Requests\Library\UpdateLibraryRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class UpdateLibraryTest extends Base
{
    public function test_library_updated(): void
    {
        $mockClient = MockClient::global([
            UpdateLibraryRequest::class => MockResponse::fixture('libraries-update'),
        ]);

        $connector = $this->connector($mockClient);

        $data = new LibraryConfiguration(
            database: [
                'index_fields' => ['resource_id'],
            ],
            text: [
                'n_context_chunk' => 10,
                'chunk_length' => 490,
                'chunk_overlap' => 10,
            ]
        );

        $connector->libraries()->update('test', $data);

        $mockClient->assertSent(UpdateLibraryRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/libraries/test' &&
                ! array_key_exists('name', $body);
        });
    }

    public function test_cannot_update_non_existing_library(): void
    {
        $mockClient = MockClient::global([
            UpdateLibraryRequest::class => MockResponse::fixture('libraries-get-not-found'),
        ]);

        $connector = $this->connector($mockClient);

        $this->expectException(NotFoundException::class);

        $data = new LibraryConfiguration(
            database: [
                'index_fields' => ['resource_id'],
            ],
            text: [
                'n_context_chunk' => 10,
                'chunk_length' => 490,
                'chunk_overlap' => 10,
            ]
        );

        $connector->libraries()->update('test-non-existing', $data);

        $mockClient->assertSent(UpdateLibraryRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/libraries/test-non-existing';
        });
    }
}
