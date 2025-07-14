<?php

namespace OneOffTech\LibrarianClient\Requests\Embeddings;

use OneOffTech\LibrarianClient\Dto\Document;
use OneOffTech\LibrarianClient\Dto\Embeddings;
use OneOffTech\LibrarianClient\Dto\Extraction;
use OneOffTech\LibrarianClient\Dto\ResponseSchema;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EmbeddingsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly array $text,
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/embeddings";
    }

    protected function defaultBody(): array
    {
        return [
            'corpus' => $this->text,
        ];
    }

    public function createDtoFromResponse(Response $response): Embeddings
    {
        $data = $response->json();

        return (new Embeddings(
            vectors: $data,
        ))->setResponse($response);
    }
}
