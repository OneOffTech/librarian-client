<?php

namespace OneOffTech\LibrarianClient\Requests\Document;

use OneOffTech\LibrarianClient\Dto\DocumentsCollection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class AllDocumentRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $library_id,
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/documents";
    }

    public function createDtoFromResponse(Response $response): DocumentsCollection
    {
        $data = $response->json('results');

        return (new DocumentsCollection(
            items: $data ?? [],
        ))->setResponse($response);
    }
}
