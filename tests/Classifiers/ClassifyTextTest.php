<?php

namespace OneOffTech\LibrarianClient\Tests\Classifiers;

use OneOffTech\LibrarianClient\Dto\Text;
use OneOffTech\LibrarianClient\Exceptions\ValidationException;
use OneOffTech\LibrarianClient\Requests\Classifier\ClassifyTextRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class ClassifyTextTest extends Base
{
    public function test_text_classified(): void
    {
        $mockClient = MockClient::global([
            ClassifyTextRequest::class => MockResponse::fixture('classifier-text'),
        ]);

        $connector = $this->connector($mockClient);

        $data = new Text('text-id', 'en', 'This is the content to be classified.');

        $classification = $connector->classifiers('localhost')->classify('test-classifier-id', $data);

        $this->assertEquals('text-id', $classification->id);

        $this->assertEquals('test-classifier-id', $classification->classifier);

        $this->assertCount(16, $classification->results->items);

        $this->assertEquals([
            'name' => 'sdg9',
            'score' => 0.15923157,
            'n_matches' => 1,
            'matches' => [
                0 => [
                    'text' => 'This is the content to be classified.',
                    'score' => 0.15923157,
                    'match_position' => [
                        'length' => 37,
                        'page' => 1,
                    ],
                ],
            ],
        ], $classification->results->items[0]);

        $mockClient->assertSent(ClassifyTextRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/classify' &&
                $body['classifier'] === 'test-classifier-id' &&
                $body['text']['id'] === 'text-id' &&
                $body['text']['lang'] === 'en' &&
                $body['text']['text'] === 'This is the content to be classified.';
        });
    }

    public function test_id_required(): void
    {
        $mockClient = MockClient::global([
            ClassifyTextRequest::class => MockResponse::make(
                body: ['detail' => 'Not found'],
                status: 404,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $this->expectException(ValidationException::class);

        $data = new Text('', 'en', 'This is the content to be classified.');

        $classification = $connector->classifiers('localhost')->classify('test-classifier-id', $data);

        $mockClient->assertNotSent(ClassifyTextRequest::class);
    }

    public function test_language_required(): void
    {
        $mockClient = MockClient::global([
            ClassifyTextRequest::class => MockResponse::make(
                body: ['detail' => 'Not found'],
                status: 404,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $this->expectException(ValidationException::class);

        $data = new Text('text-id', '', 'This is the content to be classified.');

        $classification = $connector->classifiers('localhost')->classify('test-classifier-id', $data);

        $mockClient->assertNotSent(ClassifyTextRequest::class);
    }

    public function test_text_content_required(): void
    {
        $mockClient = MockClient::global([
            ClassifyTextRequest::class => MockResponse::make(
                body: ['detail' => 'Not found'],
                status: 404,
                headers: ['Content-Type' => 'application/json']
            ),
        ]);

        $connector = $this->connector($mockClient);

        $this->expectException(ValidationException::class);

        $data = new Text('text-id', 'en', '');

        $classification = $connector->classifiers('localhost')->classify('test-classifier-id', $data);

        $mockClient->assertNotSent(ClassifyTextRequest::class);
    }
}
