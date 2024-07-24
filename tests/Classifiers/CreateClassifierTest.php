<?php

namespace OneOffTech\LibrarianClient\Tests\Classifiers;

use OneOffTech\LibrarianClient\Dto\Classifier;
use OneOffTech\LibrarianClient\Exceptions\ValidationException;
use OneOffTech\LibrarianClient\Requests\Classifier\CreateClassifierRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class CreateClassifierTest extends Base
{
    public function test_classifier_created(): void
    {
        $mockClient = MockClient::global([
            CreateClassifierRequest::class => MockResponse::fixture('classifier-create'),
        ]);

        $connector = $this->connector($mockClient);

        $data = new Classifier('test-classifier-id', 'http://sdg-classifier/', 'sdg');

        $connector->classifiers('localhost')->create($data);

        $mockClient->assertSent(CreateClassifierRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/classifiers' &&
                $body['id'] === 'test-classifier-id' &&
                $body['name'] === 'sdg' &&
                $body['url'] === 'http://sdg-classifier';
        });
    }

    public function test_classifier_not_created_with_empty_id(): void
    {
        $mockClient = MockClient::global([
            CreateClassifierRequest::class => MockResponse::make(
                body: ['detail' => 'Not found'],
                status: 404,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $data = new Classifier('', 'http://sdg-classifier/', 'sdg');

        $this->expectException(ValidationException::class);

        $connector->classifiers('localhost')->create($data);

        $mockClient->assertNotSent(CreateClassifierRequest::class);
    }

    public function test_classifier_not_created_with_empty_url(): void
    {
        $mockClient = MockClient::global([
            CreateClassifierRequest::class => MockResponse::make(
                body: ['detail' => 'Not found'],
                status: 404,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $data = new Classifier('test-classifier', '', 'sdg');

        $this->expectException(ValidationException::class);

        $connector->classifiers('localhost')->create($data);

        $mockClient->assertNotSent(CreateClassifierRequest::class);
    }
}
