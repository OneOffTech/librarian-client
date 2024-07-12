<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class DocumentsCollection implements WithResponse
{
    use HasResponse;

    public function __construct(
        public readonly array $items,
    ) {}
}
