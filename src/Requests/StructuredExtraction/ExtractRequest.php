<?php

namespace OneOffTech\LibrarianClient\Requests\StructuredExtraction;

use OneOffTech\LibrarianClient\Dto\Document;
use OneOffTech\LibrarianClient\Dto\Extraction;
use OneOffTech\LibrarianClient\Dto\ResponseSchema;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ExtractRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $library_id,
        protected readonly Document $document,
        protected readonly ResponseSchema $responseSchema,
        protected readonly ?array $sections = null,
        protected readonly ?string $instructions = null,
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/structured_extract";
    }

    protected function defaultBody(): array
    {
        return [
            'doc' => $this->document->data,
            'response_model' => $this->responseSchema->schema,
            'instructions' => $this->instructions ?? '',
            'sections' => $this->sections,
        ];
    }

    public function createDtoFromResponse(Response $response): Extraction
    {
        $data = $response->json();

        return (new Extraction(
            content: $data,
        ))->setResponse($response);
    }
}
