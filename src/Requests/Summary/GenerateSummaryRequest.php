<?php

namespace OneOffTech\LibrarianClient\Requests\Summary;

use OneOffTech\LibrarianClient\Dto\Text;
use OneOffTech\LibrarianClient\Exceptions\ValidationException as ExceptionsValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class GenerateSummaryRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $library_id,
        protected readonly Text $text,
        protected readonly ?string $prompt = null,
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/summary";
    }

    protected function defaultBody(): array
    {
        return [
            'text' => [
                'id' => $this->text->id,
                'lang' => $this->text->language,
                'text' => $this->text->content,
            ],
            'prompt' => $this->prompt,
        ];
    }

    public function validate(): self
    {
        $validator = Validator::attribute('id', Validator::stringType()->notEmpty())
            ->attribute('language', Validator::stringType()->notEmpty())
            ->attribute('content', Validator::stringType()->notEmpty());

        try {
            $validator->check($this->text);

            return $this;

        } catch (ValidationException $exception) {
            throw new ExceptionsValidationException($exception->getMessage(), 422, $exception);
        }
    }

    public function createDtoFromResponse(Response $response): Text
    {
        $data = $response->json();

        return (new Text(
            id: $data['id'],
            language: $data['lang'],
            content: $data['text'],
        ))->setResponse($response);
    }
}
