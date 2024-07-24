<?php

namespace OneOffTech\LibrarianClient\Tests\Classifiers;

use OneOffTech\LibrarianClient\Requests\Classifier\DeleteClassifierRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class DeleteClassifierTest extends Base
{
    public function test_classifier_deleted(): void
    {
        $mockClient = MockClient::global([
            DeleteClassifierRequest::class => MockResponse::fixture('classifiers-delete'),
        ]);

        $connector = $this->connector($mockClient);

        $connector->classifiers('localhost')->delete('test-classifier-id');

        $mockClient->assertSent(DeleteClassifierRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/classifiers/test-classifier-id';
        });
    }

    public function test_cannot_delete_non_existing_classifier(): void
    {
        $mockClient = MockClient::global([
            DeleteClassifierRequest::class => MockResponse::make(
                body: ['detail' => 'Not found'],
                status: 404,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $this->expectException(NotFoundException::class);

        $connector->classifiers('localhost')->delete('non-existing');

        $mockClient->assertSent(DeleteClassifierRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/classifiers/non-existing';
        });
    }
}
