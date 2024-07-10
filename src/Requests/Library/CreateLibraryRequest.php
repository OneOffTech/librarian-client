<?php

namespace OneOffTech\LibrarianClient\Requests\Library;

use OneOffTech\LibrarianClient\Dto\Library;
use OneOffTech\LibrarianClient\Exceptions\ValidationException as ExceptionsValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateLibraryRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly Library $library
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/libraries/';
    }

    protected function defaultBody(): array
    {
        return [
            'id' => $this->library->id,
            'name' => $this->library->name,
            'config' => $this->library->configuration,
        ];
    }

    public function validate(): self
    {
        $validator = Validator::attribute('id', Validator::stringType()->notEmpty())
            ->attribute('name', Validator::optional(Validator::stringType()));

        try {
            $validator->check($this->library);

            return $this;

        } catch (ValidationException $exception) {
            throw new ExceptionsValidationException($exception->getMessage(), 422, $exception);
        }
    }
}
