<?php

namespace OneOffTech\LibrarianClient\Requests\Question;

use OneOffTech\LibrarianClient\Dto\Answer;
use OneOffTech\LibrarianClient\Dto\AnswerCollection;
use OneOffTech\LibrarianClient\Dto\Question;
use OneOffTech\LibrarianClient\Dto\QuestionTransformation;
use OneOffTech\LibrarianClient\Exceptions\ValidationException as ExceptionsValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class AggregateQuestionsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $library_id,
        protected readonly Question $question,
        protected readonly AnswerCollection $answers,
        protected readonly QuestionTransformation $transform,
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/questions/aggregate";
    }

    protected function defaultBody(): array
    {
        return [
            'question' => [
                'id' => $this->question->id,
                'lang' => $this->question->language,
                'text' => $this->question->text,
            ],
            'answers' => $this->answers->items,
            'transformation' => [
                'id' => $this->transform->id->value,
                'args' => $this->transform->args,
                'append' => $this->transform->append,
            ],
        ];
    }

    public function validate(): self
    {
        $validator = Validator::attribute('id', Validator::stringType()->notEmpty())
            ->attribute('language', Validator::stringType()->notEmpty());

        try {
            $validator->check($this->question);

            return $this;

        } catch (ValidationException $exception) {
            throw new ExceptionsValidationException($exception->getMessage(), 422, $exception);
        }
    }

    public function createDtoFromResponse(Response $response): Answer
    {
        $data = $response->json();

        return (new Answer(
            id: $data['id'],
            lang: $data['lang'],
            text: $data['text'],
            refs: $data['refs'],
        ))->setResponse($response);
    }
}
