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

    public static function default(): self
    {
        return new LibraryConfiguration(
            database: [
                'index_fields' => ['resource_id'],
            ],
            text: [
                'n_context_chunk' => 10,
                'chunk_length' => 490,
                'chunk_overlap' => 10,
            ]
        );
    }
}
