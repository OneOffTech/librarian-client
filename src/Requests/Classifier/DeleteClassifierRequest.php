<?php

namespace OneOffTech\LibrarianClient\Requests\Classifier;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteClassifierRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $library_id,
        protected readonly string $document_id
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/classifiers/{$this->document_id}";
    }
}
