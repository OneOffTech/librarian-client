<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class LibraryConfiguration implements WithResponse
{
    use HasResponse;

    public function __construct(
        public readonly array $database,
        public readonly array $text,
    ) {}

    // "database" => [
    //             "index_fields" => $this->config['library-settings']['indexed-fields'] ?? ['resource_id']
    //         ],
    //         "text" => $this->config['library-settings']['text-processing'] ?? [
    //             "n_context_chunk" => 10,
    //             "chunk_length" => 490,
    //             "chunk_overlap" => 10
    //         ],
}
