<?php

namespace OneOffTech\LibrarianClient\Tests\Classifiers;

use OneOffTech\LibrarianClient\Requests\Classifier\GetClassifierRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class GetClassifierTest extends Base
{
    public function test_classifier_retrieved(): void
    {
        $mockClient = MockClient::global([
            GetClassifierRequest::class => MockResponse::fixture('classifiers-get'),
        ]);

        $connector = $this->connector($mockClient);

        $classifier = $connector->classifiers('localhost')->get('test-classifier-id');

        $this->assertEquals('test-classifier-id', $classifier->id);

        $this->assertEquals('sdg', $classifier->name);

        $this->assertEquals('http://sdg-classifier/', $classifier->url);

        $mockClient->assertSent(GetClassifierRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/classifiers/test-classifier-id';
        });
    }

    public function test_not_existing_classifier(): void
    {
        $mockClient = MockClient::global([
            GetClassifierRequest::class => MockResponse::make(
                body: ['detail' => 'Not found'],
                status: 404,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $this->expectException(NotFoundException::class);

        $connector->classifiers('localhost')->get('not-exist');

        $mockClient->assertSent(GetClassifierRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/classifiers/not-exist';
        });
    }
}
