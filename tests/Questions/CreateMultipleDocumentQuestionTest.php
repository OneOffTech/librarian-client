<?php

namespace OneOffTech\LibrarianClient\Tests\Questions;

use OneOffTech\LibrarianClient\Dto\Answer;
use OneOffTech\LibrarianClient\Dto\AnswerCollection;
use OneOffTech\LibrarianClient\Dto\Question;
use OneOffTech\LibrarianClient\Dto\QuestionTransformation;
use OneOffTech\LibrarianClient\Requests\Question\AggregateQuestionsRequest;
use OneOffTech\LibrarianClient\Requests\Question\TransformQuestionRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use OneOffTech\LibrarianClient\TransformType;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class CreateMultipleDocumentQuestionTest extends Base
{
    public function test_question_transformed(): void
    {
        $mockClient = MockClient::global([
            TransformQuestionRequest::class => MockResponse::fixture('questions-transform'),
        ]);

        $connector = $this->connector($mockClient);

        $question = new Question('q-id', 'en', 'Question text');

        $transformation = new QuestionTransformation(
            id: TransformType::FREE_FORM,
            args: ['Question text']
        );

        $transformedQuestion = $connector->questions('localhost')->transform($question, $transformation);

        $this->assertEquals('q-id', $transformedQuestion->id);

        $this->assertEquals('en', $transformedQuestion->language);

        $this->assertEquals('Question text', $transformedQuestion->text);

        $mockClient->assertSent(TransformQuestionRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/questions/transform' &&
                $body['question']['id'] === 'q-id' &&
                $body['question']['lang'] === 'en' &&
                $body['question']['text'] === 'Question text' &&
                $body['transformation']['id'] === '0' &&
                $body['transformation']['args'][0] === 'Question text';
        });
    }

    public function test_multiple_question_aggregated(): void
    {
        $mockClient = MockClient::global([
            AggregateQuestionsRequest::class => MockResponse::fixture('questions-aggregate'),
        ]);

        $connector = $this->connector($mockClient);

        $question = new Question('q-id', 'en', 'Question text');

        $answerCollection = new AnswerCollection([
            new Answer('a-1', 'en', 'Answer Text'),
            new Answer('a-2', 'en', 'Second Answer Text'),
        ]);

        $transformation = new QuestionTransformation(
            id: TransformType::FREE_FORM,
            args: ['Question text'],
        );

        $aggregatedAnswer = $connector->questions('localhost')->aggregate(
            $question,
            $answerCollection,
            $transformation
        );

        $this->assertEquals('q-id', $aggregatedAnswer->id);

        $this->assertEquals('en', $aggregatedAnswer->lang);

        $this->assertEquals('Aggregated answer.', $aggregatedAnswer->text);

        $mockClient->assertSent(AggregateQuestionsRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/questions/aggregate' &&
                $body['question']['id'] === 'q-id' &&
                $body['question']['lang'] === 'en' &&
                $body['question']['text'] === 'Question text' &&
                $body['answers'][0]->id === 'a-1' &&
                $body['transformation']['id'] === '0' &&
                $body['transformation']['args'][0] === 'Question text';
        });
    }
}
