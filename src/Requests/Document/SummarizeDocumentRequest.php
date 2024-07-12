<?php

namespace OneOffTech\LibrarianClient\Requests\Document;

use OneOffTech\LibrarianClient\Dto\Text;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class SummarizeDocumentRequest extends Request
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
        return "/library/{$this->library_id}/documents/{$this->document_id}/summary";
    }

    public function createDtoFromResponse(Response $response): Text
    {
        $data = $response->json();

        return (new Text(
            id: $data['id'],
            language: $data['lang'],
            content: $data['text'],
        ))->setResponse($response);
    }
}
