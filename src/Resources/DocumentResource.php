<?php

namespace OneOffTech\LibrarianClient\Resources;

use OneOffTech\LibrarianClient\Dto\Answer;
use OneOffTech\LibrarianClient\Dto\Document;
use OneOffTech\LibrarianClient\Dto\DocumentsCollection;
use OneOffTech\LibrarianClient\Dto\Question;
use OneOffTech\LibrarianClient\Dto\Text;
use OneOffTech\LibrarianClient\Dto\TextClassification;
use OneOffTech\LibrarianClient\Requests\Classifier\ClassifyDocumentRequest;
use OneOffTech\LibrarianClient\Requests\Document\AllDocumentRequest;
use OneOffTech\LibrarianClient\Requests\Document\CreateDocumentRequest;
use OneOffTech\LibrarianClient\Requests\Document\DeleteDocumentRequest;
use OneOffTech\LibrarianClient\Requests\Document\GetDocumentRequest;
use OneOffTech\LibrarianClient\Requests\Document\QuestionDocumentRequest;
use OneOffTech\LibrarianClient\Requests\Document\SummarizeDocumentRequest;
use OneOffTech\LibrarianClient\Responses\LibrarianResponse;
use Saloon\Http\BaseResource;
use Saloon\Http\Connector;

class DocumentResource extends BaseResource
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

    public function all(): DocumentsCollection
    {
        return $this->connector->send(new AllDocumentRequest($this->library_id))->dto();
    }

    public function get(string $id): Document
    {
        return $this->connector->send(new GetDocumentRequest($this->library_id, $id))->dto();
    }

    public function summarize(string $id): Text
    {
        return $this->connector->send(new SummarizeDocumentRequest($this->library_id, $id))->dto();
    }

    public function ask(string $id, Question $question): Answer
    {
        return $this->connector->send((new QuestionDocumentRequest($this->library_id, $id, $question))->validate())->dto();
    }

    public function classify(string $classifier_id, string $document_id): TextClassification
    {
        return $this->connector->send((new ClassifyDocumentRequest($this->library_id, $classifier_id, $document_id)))->dto();
    }

    public function create(Document $document): LibrarianResponse
    {
        return $this->connector->send((new CreateDocumentRequest($this->library_id, $document))->validate());
    }

    public function delete(string $id): LibrarianResponse
    {
        return $this->connector->send(new DeleteDocumentRequest($this->library_id, $id));
    }
}
