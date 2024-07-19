<?php

namespace OneOffTech\LibrarianClient\Dto;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class AnswerReference implements WithResponse
{
    use HasResponse;

    public function __construct(
        public readonly int $page_number,
        public readonly float $score,
        public readonly ?array $bounding_box = null,
        public readonly ?string $id = null,
    ) {}
}
