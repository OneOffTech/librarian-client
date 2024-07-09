<?php

namespace OneOffTech\LibrarianClient\Tests\Libraries;

use OneOffTech\LibrarianClient\Requests\Library\AllLibraryRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class AllLibraryTest extends Base
{
    public function test_all_libraries_listed(): void
    {
        $mockClient = MockClient::global([
            AllLibraryRequest::class => MockResponse::fixture('libraries-all'),
        ]);

        $connector = $this->connector($mockClient);

        $libraries = $connector->libraries()->all();

        $this->assertEquals(['localhost'], $libraries->items);

        $mockClient->assertSent(AllLibraryRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/libraries';
        });
    }

    public function test_empty_libraries_list(): void
    {
        $mockClient = MockClient::global([
            AllLibraryRequest::class => MockResponse::make(
                body: ['results' => []],
                status: 200,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $libraries = $connector->libraries()->all();

        $this->assertEquals([], $libraries->items);

        $mockClient->assertSent(AllLibraryRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/libraries';
        });
    }
}
