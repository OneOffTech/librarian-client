<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class Classifier implements WithResponse
{
    use HasResponse;

    public function __construct(
        public readonly string $id,
        public readonly string $url,
        public readonly ?string $name = null,
    ) {}
}
