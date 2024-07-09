<?php

namespace OneOffTech\LibrarianClient\Requests\Library;

use OneOffTech\LibrarianClient\Dto\LibrariesCollection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class AllLibraryRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct()
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/libraries';
    }

    public function createDtoFromResponse(Response $response): LibrariesCollection
    {
        $data = $response->json('results');

        return (new LibrariesCollection(
            items: $data ?? [],
        ))->setResponse($response);
    }
}
