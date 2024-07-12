<?php

namespace OneOffTech\LibrarianClient\Requests\Document;

use OneOffTech\LibrarianClient\Dto\Document;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDocumentRequest extends Request
{
    protected Method $method = Method::GET;

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

    public function createDtoFromResponse(Response $response): Document
    {
        $data = $response->json();

        return (new Document(
            id: $data['id'],
            language: $data['lang'],
            data: $data['data'],
        ))->setResponse($response);
    }
}
