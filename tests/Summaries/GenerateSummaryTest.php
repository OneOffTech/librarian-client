<?php

namespace OneOffTech\LibrarianClient\Tests\Summaries;

use OneOffTech\LibrarianClient\Dto\Text;
use OneOffTech\LibrarianClient\Exceptions\ValidationException;
use OneOffTech\LibrarianClient\Requests\Summary\GenerateSummaryRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Exceptions\Request\Statuses\UnprocessableEntityException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class GenerateSummaryTest extends Base
{
    public function test_summary_generated(): void
    {
        $mockClient = MockClient::global([
            GenerateSummaryRequest::class => MockResponse::fixture('summaries-generate'),
        ]);

        $connector = $this->connector($mockClient);

        $data = new Text(
            id: 'id',
            language: 'en',
            content: 'The text to summarize',
        );

        $summary = $connector->summaries('localhost')->generate($data);

        $mockClient->assertSent(GenerateSummaryRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/summary' &&
                $body['id'] === 'id' &&
                $body['lang'] === 'en' &&
                $body['text'] === 'The text to summarize';
        });

        $this->assertEquals('id', $summary->id);
        $this->assertEquals('en', $summary->language);
        $this->assertEquals('The summary.', $summary->content);
    }

    public function test_text_content_required(): void
    {
        $mockClient = MockClient::global([
            GenerateSummaryRequest::class => MockResponse::fixture('summaries-generate'),
        ]);

        $connector = $this->connector($mockClient);

        $data = new Text(
            id: 'id',
            language: 'en',
            content: '',
        );

        $this->expectException(ValidationException::class);

        $connector->summaries('localhost')->generate($data);

        $mockClient->assertNotSent(GenerateSummaryRequest::class);
    }

    public function test_text_id_required(): void
    {
        $mockClient = MockClient::global([
            GenerateSummaryRequest::class => MockResponse::fixture('summaries-generate'),
        ]);

        $connector = $this->connector($mockClient);

        $data = new Text(
            id: '',
            language: 'en',
            content: 'The content',
        );

        $this->expectException(ValidationException::class);

        $connector->summaries('localhost')->generate($data);

        $mockClient->assertNotSent(GenerateSummaryRequest::class);
    }

    public function test_text_language_required(): void
    {
        $mockClient = MockClient::global([
            GenerateSummaryRequest::class => MockResponse::fixture('summaries-generate'),
        ]);

        $connector = $this->connector($mockClient);

        $data = new Text(
            id: 'id',
            language: '',
            content: 'The content',
        );

        $this->expectException(ValidationException::class);

        $connector->summaries('localhost')->generate($data);

        $mockClient->assertNotSent(GenerateSummaryRequest::class);
    }

    public function test_unsupported_language(): void
    {
        $mockClient = MockClient::global([
            GenerateSummaryRequest::class => MockResponse::fixture('summaries-generate-unsupported-language'),
        ]);

        $connector = $this->connector($mockClient);

        $data = new Text(
            id: 'id',
            language: 'zz',
            content: 'The content',
        );

        $this->expectException(UnprocessableEntityException::class);

        $connector->summaries('localhost')->generate($data);

        $mockClient->assertSent(GenerateSummaryRequest::class);
    }
}
