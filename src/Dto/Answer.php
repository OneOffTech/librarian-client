<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class Answer implements WithResponse
{
    use HasResponse;

    public function __construct(
        public readonly string $id,
        public readonly string $lang,
        public readonly string $text,
        public readonly array $refs = [],
    ) {}
}
