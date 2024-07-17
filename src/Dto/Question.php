<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class Question implements WithResponse
{
    use HasResponse;

    public function __construct(
        public readonly string $id,
        public readonly string $language,
        public readonly string $content,
    ) {}
}
