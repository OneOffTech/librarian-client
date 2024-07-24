<?php

namespace OneOffTech\LibrarianClient\Requests\Classifier;

use OneOffTech\LibrarianClient\Dto\ClassificationResultCollection;
use OneOffTech\LibrarianClient\Dto\Text;
use OneOffTech\LibrarianClient\Dto\TextClassification;
use OneOffTech\LibrarianClient\Exceptions\ValidationException as ExceptionsValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ClassifyTextRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $library_id,
        protected readonly string $classifier_id,
        protected readonly Text $text
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/classify";
    }

    protected function defaultBody(): array
    {
        return [
            'classifier' => $this->classifier_id,
            'text' => [
                'id' => $this->text->id,
                'lang' => $this->text->language,
                'text' => $this->text->content,
            ],
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

    public function createDtoFromResponse(Response $response): TextClassification
    {
        $data = $response->json();

        return (new TextClassification(
            id: $data['id'],
            classifier: $data['classifier']['id'],
            results: new ClassificationResultCollection($data['results']),
        ))->setResponse($response);
    }
}
