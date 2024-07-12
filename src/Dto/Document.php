<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class Document implements WithResponse
{
    use HasResponse;

    public function __construct(
        public readonly string $id,
        public readonly string $language,
        public readonly array $data,
    ) {}
}
