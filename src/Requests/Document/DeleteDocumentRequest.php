<?php

namespace OneOffTech\LibrarianClient\Requests\Document;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteDocumentRequest extends Request
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
        return "/library/{$this->library_id}/documents/{$this->document_id}";
    }
}
