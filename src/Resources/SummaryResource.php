<?php

namespace OneOffTech\LibrarianClient\Resources;

use OneOffTech\LibrarianClient\Dto\Text;
use OneOffTech\LibrarianClient\Requests\Summary\GenerateSummaryRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Connector;

class SummaryResource extends BaseResource
{
    /**
     * Constructor
     */
    public function __construct(
        protected readonly string $library_id,
        Connector $connector
    ) {
        parent::__construct($connector);
    }

    /**
     * Generate a summary of the specified text
     */
    public function generate(Text $text, ?string $prompt = null): Text
    {
        return $this->connector->send((new GenerateSummaryRequest($this->library_id, $text, $prompt))->validate())->dto();
    }
}
