<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class ClassificationResultCollection implements WithResponse
{
    use HasResponse;

    public function __construct(
        /**
         * @var Answer[]
         */
        public readonly array $items,
    ) {}
}
