<?php

namespace OneOffTech\LibrarianClient\Tests;

use OneOffTech\LibrarianClient\Connectors\LibrarianConnector;
use PHPUnit\Framework\TestCase;
use Saloon\Http\Faking\MockClient;

abstract class Base extends TestCase
{
    protected function setUp(): void
    {
        MockClient::destroyGlobal();
    }

    /**
     * Create an instance of the LibrarianConnector to use during tests
     */
    protected function connector(MockClient $mockClient): LibrarianConnector
    {
        $connector = new LibrarianConnector('test', 'http://localhost:5000');

        $connector->withMockClient($mockClient);

        return $connector;
    }
}
