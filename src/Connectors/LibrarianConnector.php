<?php

namespace OneOffTech\LibrarianClient\Connectors;

use OneOffTech\LibrarianClient\Resources\DocumentResource;
use OneOffTech\LibrarianClient\Resources\LibraryResource;
use OneOffTech\LibrarianClient\Resources\PromptResource;
use OneOffTech\LibrarianClient\Resources\SummaryResource;
use OneOffTech\LibrarianClient\Responses\LibrarianResponse;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;
use SensitiveParameter;
use Throwable;

class LibrarianConnector extends Connector
{
    use AlwaysThrowOnErrors;
    use HasTimeout;

    protected int $connectTimeout = 30;

    protected int $requestTimeout = 120;

    protected ?string $response = LibrarianResponse::class;

    public function __construct(

        /**
         * The authentication token
         */
        #[SensitiveParameter]
        public readonly string $token,

        /**
         * The url on the API
         */
        protected readonly string $baseUrl,
    ) {
        //
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->token);
    }

    /**
     * Determine if the request has failed.
     */
    public function hasRequestFailed(Response $response): ?bool
    {
        return $response->serverError() || $response->clientError();
    }

    /**
     * Get the request exception.
     */
    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        // TODO: customize the exception based on the returned response
        return null;
    }

    /**
     * Manage libraries
     */
    public function libraries(): LibraryResource
    {
        return new LibraryResource($this);
    }

    /**
     * Manage prompts for the specific library
     */
    public function prompts(): PromptResource
    {
        return new PromptResource($this);
    }

    public function summaries(string $library_id): SummaryResource
    {
        return new SummaryResource($library_id, $this);
    }

    public function documents(string $library_id): DocumentResource
    {
        return new DocumentResource($library_id, $this);
    }
}
