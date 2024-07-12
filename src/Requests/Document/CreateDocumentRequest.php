<?php

namespace OneOffTech\LibrarianClient\Requests\Document;

use OneOffTech\LibrarianClient\Dto\Document;
use OneOffTech\LibrarianClient\Exceptions\ValidationException as ExceptionsValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateDocumentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $library_id,
        protected readonly Document $document
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/documents";
    }

    protected function defaultBody(): array
    {
        return [
            'id' => $this->document->id,
            'lang' => $this->document->language,
            'data' => $this->document->data,
        ];
    }

    public function validate(): self
    {
        $validator = Validator::attribute('id', Validator::stringType()->notEmpty())
            ->attribute('language', Validator::stringType()->notEmpty());

        try {
            $validator->check($this->document);

            return $this;

        } catch (ValidationException $exception) {
            throw new ExceptionsValidationException($exception->getMessage(), 422, $exception);
        }
    }
}
