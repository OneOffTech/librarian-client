<?php

namespace OneOffTech\LibrarianClient\Resources;

use OneOffTech\LibrarianClient\Requests\Prompt\UpdatePromptsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class PromptResource extends BaseResource
{
    /**
     * Synchronize prompts for the specific library
     */
    public function sync(): Response
    {
        return $this->connector->send(new UpdatePromptsRequest());
    }
}
