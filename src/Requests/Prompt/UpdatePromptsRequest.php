<?php

namespace OneOffTech\LibrarianClient\Requests\Prompt;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class UpdatePromptsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct()
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/prompts/update';
    }
}
