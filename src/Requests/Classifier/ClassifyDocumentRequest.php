<?php

namespace OneOffTech\LibrarianClient\Requests\Classifier;

use OneOffTech\LibrarianClient\Dto\ClassificationResultCollection;
use OneOffTech\LibrarianClient\Dto\TextClassification;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ClassifyDocumentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $library_id,
        protected readonly string $classifier_id,
        protected readonly string $document_id
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/documents/{$this->document_id}/classify";
    }

    protected function defaultBody(): array
    {
        return [
            'classifier' => $this->classifier_id,
        ];
    }

    public function createDtoFromResponse(Response $response): TextClassification
    {
        $data = $response->json();

        return (new TextClassification(
            id: $data['id'],
            classifier: $data['classifier']['id'],
            results: new ClassificationResultCollection($data['results']),
        ))->setResponse($response);
    }
}
