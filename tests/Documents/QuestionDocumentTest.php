<?php

namespace OneOffTech\LibrarianClient\Tests\Documents;

use OneOffTech\LibrarianClient\Dto\Question;
use OneOffTech\LibrarianClient\Requests\Document\QuestionDocumentRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class QuestionDocumentTest extends Base
{
    public function test_question_asked_to_document(): void
    {
        $mockClient = MockClient::global([
            QuestionDocumentRequest::class => MockResponse::fixture('document-question'),
        ]);

        $connector = $this->connector($mockClient);

        $question = new Question(
            id: 'question-id',
            language: 'en',
            text: 'This is a question',
        );

        $answer = $connector->documents('localhost')->ask('d-1', $question);

        $this->assertEquals('question-id', $answer->id);

        $this->assertEquals('en', $answer->lang);

        $this->assertEquals('The answer.', $answer->text);

        $this->assertEquals([
            [
                'id' => 'd1',
                'page_number' => 20,
            ],
        ], $answer->refs);

        $mockClient->assertSent(QuestionDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/documents/d-1/questions';
        });
    }

    public function test_answer_with_no_references(): void
    {
        $mockClient = MockClient::global([
            QuestionDocumentRequest::class => MockResponse::fixture('document-question-no-refs'),
        ]);

        $connector = $this->connector($mockClient);

        $question = new Question(
            id: 'question-id',
            language: 'en',
            text: 'This is a question',
        );

        $answer = $connector->documents('localhost')->ask('d-1', $question);

        $this->assertEquals('question-id', $answer->id);

        $this->assertEquals('en', $answer->lang);

        $this->assertEquals('The answer.', $answer->text);

        $this->assertEmpty($answer->refs);

        $mockClient->assertSent(QuestionDocumentRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/library/localhost/documents/d-1/questions';
        });
    }
}
