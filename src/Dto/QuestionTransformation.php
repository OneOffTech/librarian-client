<?php

namespace OneOffTech\LibrarianClient\Dto;

use OneOffTech\LibrarianClient\TransformType;
use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class QuestionTransformation implements WithResponse
{
    use HasResponse;

    public function __construct(
        public readonly TransformType $id,
        public readonly array $args = [],
        public readonly array $append = [], // each entry has id,text
    ) {}
}
