<?php

namespace OneOffTech\LibrarianClient\Requests\Classifier;

use OneOffTech\LibrarianClient\Dto\Classifier;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetClassifierRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $library_id,
        protected readonly string $classifier_id
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/library/{$this->library_id}/classifiers/{$this->classifier_id}";
    }

    public function createDtoFromResponse(Response $response): Classifier
    {
        $data = $response->json();

        return (new Classifier(
            id: $data['id'],
            name: $data['name'],
            url: $data['url'],
        ))->setResponse($response);
    }
}
