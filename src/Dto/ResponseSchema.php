<?php

namespace OneOffTech\LibrarianClient\Dto;

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
