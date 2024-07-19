<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class AnswerReferenceCollection implements WithResponse
{
    use HasResponse;

    public function __construct(
        /**
         * @var AnswerReference[]
         */
        public readonly array $items,
    ) {}
}
