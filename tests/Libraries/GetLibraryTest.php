<?php

namespace OneOffTech\LibrarianClient\Tests\Libraries;

use OneOffTech\LibrarianClient\Requests\Library\GetLibraryRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class GetLibraryTest extends Base
{
    public function test_library_retrieved(): void
    {
        $mockClient = MockClient::global([
            GetLibraryRequest::class => MockResponse::fixture('libraries-get'),
        ]);

        $connector = $this->connector($mockClient);

        $library = $connector->libraries()->get('localhost');

        $this->assertEquals('localhost', $library->id);

        $this->assertEquals('Localhost Library', $library->name);

        $mockClient->assertSent(GetLibraryRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/libraries/localhost';
        });
    }

    public function test_not_existing_library(): void
    {
        $mockClient = MockClient::global([
            GetLibraryRequest::class => MockResponse::fixture('libraries-get-not-found'),
        ]);

        $connector = $this->connector($mockClient);

        $this->expectException(NotFoundException::class);

        $connector->libraries()->get('library');

        $mockClient->assertSent(GetLibraryRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/libraries/library';
        });
    }
}
