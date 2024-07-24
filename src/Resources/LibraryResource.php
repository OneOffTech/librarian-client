<?php

namespace OneOffTech\LibrarianClient\Resources;

use OneOffTech\LibrarianClient\Dto\LibrariesCollection;
use OneOffTech\LibrarianClient\Dto\Library;
use OneOffTech\LibrarianClient\Dto\LibraryConfiguration;
use OneOffTech\LibrarianClient\Requests\Library\AllLibraryRequest;
use OneOffTech\LibrarianClient\Requests\Library\CreateLibraryRequest;
use OneOffTech\LibrarianClient\Requests\Library\DeleteLibraryRequest;
use OneOffTech\LibrarianClient\Requests\Library\GetLibraryRequest;
use OneOffTech\LibrarianClient\Requests\Library\UpdateLibraryRequest;
use OneOffTech\LibrarianClient\Responses\LibrarianResponse;
use Saloon\Http\BaseResource;

class LibraryResource extends BaseResource
{
    public function all(): LibrariesCollection
    {
        return $this->connector->send(new AllLibraryRequest)->dto();
    }

    public function get(string $id): Library
    {
        return $this->connector->send(new GetLibraryRequest($id))->dto();
    }

    public function create(Library $library): LibrarianResponse
    {
        return $this->connector->send((new CreateLibraryRequest($library))->validate());
    }

    public function update(string $id, LibraryConfiguration $configuration): LibrarianResponse
    {
        return $this->connector->send(new UpdateLibraryRequest($id, $configuration));
    }

    public function delete(string $id): LibrarianResponse
    {
        return $this->connector->send(new DeleteLibraryRequest($id));
    }
}
