<?php

namespace OneOffTech\LibrarianClient\Resources;

use OneOffTech\LibrarianClient\Dto\Answer;
use OneOffTech\LibrarianClient\Dto\AnswerCollection;
use OneOffTech\LibrarianClient\Dto\Question;
use OneOffTech\LibrarianClient\Dto\QuestionTransformation;
use OneOffTech\LibrarianClient\Requests\Question\AggregateQuestionsRequest;
use OneOffTech\LibrarianClient\Requests\Question\TransformQuestionRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Connector;

class QuestionResource extends BaseResource
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

    public function transform(Question $question, QuestionTransformation $transformation): Question
    {
        return $this->connector->send((new TransformQuestionRequest($this->library_id, $question, $transformation))->validate())->dto();
    }

    public function aggregate(Question $question, AnswerCollection $answers, QuestionTransformation $transformation): Answer
    {
        return $this->connector->send((new AggregateQuestionsRequest($this->library_id, $question, $answers, $transformation))->validate())->dto();
    }
}
