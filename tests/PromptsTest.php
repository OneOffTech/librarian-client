<?php

namespace OneOffTech\LibrarianClient\Tests;

use OneOffTech\LibrarianClient\Requests\Prompt\UpdatePromptsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class PromptsTest extends Base
{
    public function test_prompts_synchronized(): void
    {
        $mockClient = MockClient::global([
            UpdatePromptsRequest::class => MockResponse::fixture('prompts'),
        ]);

        $connector = $this->connector($mockClient);

        $response = $connector->prompts()->sync();

        $this->assertEquals('Prompts updated succesfully.', $response->json('message'));

        $mockClient->assertSent(UpdatePromptsRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            return $request->resolveEndpoint() === '/prompts/update';
        });
    }
}
