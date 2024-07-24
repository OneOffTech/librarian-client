<?php

namespace OneOffTech\LibrarianClient\Requests\Classifier;

use OneOffTech\LibrarianClient\Dto\Classifier;
use OneOffTech\LibrarianClient\Exceptions\ValidationException as ExceptionsValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateClassifierRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $library_id,
        protected readonly Classifier $classifier
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/classifiers";
    }

    protected function defaultBody(): array
    {
        return [
            'id' => $this->classifier->id,
            'url' => rtrim($this->classifier->url, '/'),
            'name' => $this->classifier->name,
        ];
    }

    public function validate(): self
    {
        $validator = Validator::attribute('id', Validator::stringType()->notEmpty())
            ->attribute('url', Validator::stringType()->notEmpty());

        try {
            $validator->check($this->classifier);

            return $this;

        } catch (ValidationException $exception) {
            throw new ExceptionsValidationException($exception->getMessage(), 422, $exception);
        }
    }
}
