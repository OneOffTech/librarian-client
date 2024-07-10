<?php

namespace OneOffTech\LibrarianClient\Requests\Library;

use OneOffTech\LibrarianClient\Dto\LibraryConfiguration;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateLibraryRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly string $library_id,
        protected readonly LibraryConfiguration $configuration
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/libraries/{$this->library_id}";
    }

    protected function defaultBody(): array
    {
        return [
            'database' => $this->configuration->database,
            'text' => $this->configuration->text,
        ];
    }
}
