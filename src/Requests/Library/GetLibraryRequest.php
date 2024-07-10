<?php

namespace OneOffTech\LibrarianClient\Requests\Library;

use OneOffTech\LibrarianClient\Dto\Library;
use OneOffTech\LibrarianClient\Dto\LibraryConfiguration;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetLibraryRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $id
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/libraries/{$this->id}";
    }

    public function createDtoFromResponse(Response $response): Library
    {
        $data = $response->json();

        $config = new LibraryConfiguration(
            database: $data['config']['database'] ?? [],
            text: $data['config']['text'] ?? [],
        );

        return (new Library(
            id: $data['id'],
            name: $data['name'],
            configuration: $config,
        ))->setResponse($response);
    }
}
