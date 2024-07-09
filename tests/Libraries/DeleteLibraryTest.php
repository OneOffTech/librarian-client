<?php

namespace OneOffTech\LibrarianClient\Tests\Libraries;

use OneOffTech\LibrarianClient\Requests\Library\DeleteLibraryRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class DeleteLibraryTest extends Base
{
    public function test_library_deleted(): void
    {
        $mockClient = MockClient::global([
            DeleteLibraryRequest::class => MockResponse::fixture('libraries-delete'),
        ]);

        $connector = $this->connector($mockClient);

        $library = $connector->libraries()->delete('test-lib');

        $mockClient->assertSent(DeleteLibraryRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/libraries/test-lib';
        });
    }

    public function test_cannot_delete_non_existing_library(): void
    {
        $mockClient = MockClient::global([
            DeleteLibraryRequest::class => MockResponse::fixture('libraries-get-not-found'),
        ]);

        $connector = $this->connector($mockClient);

        $this->expectException(NotFoundException::class);

        $connector->libraries()->delete('non-existing');

        $mockClient->assertSent(DeleteLibraryRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/libraries/non-existing';
        });
    }
}
