<?php

namespace OneOffTech\LibrarianClient\Resources;

use OneOffTech\LibrarianClient\Dto\Classifier;
use OneOffTech\LibrarianClient\Dto\Document;
use OneOffTech\LibrarianClient\Dto\Extraction;
use OneOffTech\LibrarianClient\Dto\ResponseSchema;
use OneOffTech\LibrarianClient\Dto\Text;
use OneOffTech\LibrarianClient\Dto\TextClassification;
use OneOffTech\LibrarianClient\Requests\Classifier\ClassifyTextRequest;
use OneOffTech\LibrarianClient\Requests\Classifier\CreateClassifierRequest;
use OneOffTech\LibrarianClient\Requests\Classifier\DeleteClassifierRequest;
use OneOffTech\LibrarianClient\Requests\Classifier\GetClassifierRequest;
use OneOffTech\LibrarianClient\Requests\StructuredExtraction\ExtractRequest;
use OneOffTech\LibrarianClient\Responses\LibrarianResponse;
use Saloon\Http\BaseResource;
use Saloon\Http\Connector;

class StructuredExtractionResource extends BaseResource
{
    /**
     * Constructor
     */
    public function __construct(
        readonly protected string $library_id,
        Connector $connector
    ) {
        parent::__construct($connector);
    }

    public function extract(string $structuredResponseModel, Document $from, ?array $sections = null, ?string $instructions = null): Extraction
    {
        return $this->connector->send(new ExtractRequest(
                library_id: $this->library_id,
                document: $from,
                responseSchema: ResponseSchema::fromString($structuredResponseModel),
                sections: $sections,
                instructions: $instructions,
            ))
            ->dto();
    }
}
