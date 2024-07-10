<?php

namespace OneOffTech\LibrarianClient\Requests\Library;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteLibraryRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/libraries/{$this->id}";
    }
}
