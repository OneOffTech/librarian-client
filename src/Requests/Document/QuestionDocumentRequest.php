<?php

namespace OneOffTech\LibrarianClient\Requests\Document;

use OneOffTech\LibrarianClient\Dto\Answer;
use OneOffTech\LibrarianClient\Dto\Question;
use OneOffTech\LibrarianClient\Exceptions\ValidationException as ExceptionsValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class QuestionDocumentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $library_id,
        protected readonly string $document_id,
        protected readonly Question $question,
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/documents/{$this->document_id}/questions";
    }

    protected function defaultBody(): array
    {
        return [
            'id' => $this->question->id,
            'lang' => $this->question->language,
            'text' => $this->question->text,
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
