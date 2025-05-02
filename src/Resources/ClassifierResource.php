<?php

namespace OneOffTech\LibrarianClient\Resources;

use OneOffTech\LibrarianClient\Dto\Classifier;
use OneOffTech\LibrarianClient\Dto\Text;
use OneOffTech\LibrarianClient\Dto\TextClassification;
use OneOffTech\LibrarianClient\Requests\Classifier\ClassifyDocumentRequest;
use OneOffTech\LibrarianClient\Requests\Classifier\ClassifyTextRequest;
use OneOffTech\LibrarianClient\Requests\Classifier\CreateClassifierRequest;
use OneOffTech\LibrarianClient\Requests\Classifier\DeleteClassifierRequest;
use OneOffTech\LibrarianClient\Requests\Classifier\GetClassifierRequest;
use OneOffTech\LibrarianClient\Responses\LibrarianResponse;
use Saloon\Http\BaseResource;
use Saloon\Http\Connector;

class ClassifierResource extends BaseResource
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

    public function get(string $classifier_id): Classifier
    {
        return $this->connector->send(new GetClassifierRequest($this->library_id, $classifier_id))->dto();
    }

    public function create(Classifier $classifier): LibrarianResponse
    {
        return $this->connector->send((new CreateClassifierRequest($this->library_id, $classifier))->validate());
    }

    public function delete(string $classifier_id): LibrarianResponse
    {
        return $this->connector->send(new DeleteClassifierRequest($this->library_id, $classifier_id));
    }

    public function classify(string $classifier_id, Text $text): TextClassification
    {
        return $this->connector->send((new ClassifyTextRequest($this->library_id, $classifier_id, $text))->validate())->dto();
    }

    public function classifyDocument(string $classifier_id, string $document_id): TextClassification
    {
        return $this->connector->send((new ClassifyDocumentRequest($this->library_id, $classifier_id, $document_id)))->dto();
    }
}
