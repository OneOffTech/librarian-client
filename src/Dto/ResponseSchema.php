<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class ResponseSchema
{

    public function __construct(
        public readonly array $schema,
    ) {}


    public static function fromString(string $schema): static
    {
        return new static(json_decode($schema, associative: true));
    }
    
    public static function fromArray(array $schema): static
    {
        return new static($schema);
    }
}
