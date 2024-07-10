<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class Library implements WithResponse
{
    use HasResponse;

    public readonly ?LibraryConfiguration $configuration;

    public function __construct(
        public readonly string $id,
        public readonly ?string $name = null,
        ?LibraryConfiguration $configuration = null,
    ) {
        $this->configuration = $configuration ?? LibraryConfiguration::default();
    }
}
