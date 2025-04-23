<?php

namespace OneOffTech\LibrarianClient\Tests\Summaries;

use OneOffTech\LibrarianClient\Dto\Document;
use OneOffTech\LibrarianClient\Requests\StructuredExtraction\ExtractRequest;
use OneOffTech\LibrarianClient\Tests\Base;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;

class StructuredExtractionTest extends Base
{
    public function test_extraction_from_whole_document(): void
    {
        $mockClient = MockClient::global([
            ExtractRequest::class => MockResponse::fixture('structured-extract-whole-document'),
        ]);

        $connector = $this->connector($mockClient);

        $documentContent = [
            'type' => 'doc',
            'content' => [
                [
                    'category' => 'page',
                    'attributes' => [
                        'page' => 1,
                    ],
                    'content' => [
                        [
                            'role' => 'body',
                            'text' => 'This document aims to verify the structured extraction pipeline. We have some dates 22 march 2025, 15/04/2025 of relevance for the extraction.',
                            'marks' => [],
                            'attributes' => [
                                'bounding_box' => [],
                                'section' => 'Test section',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $document = new Document('test-structured-extract-id', 'en', $documentContent);

        $model = <<<'STR_MODEL'
        {
            "type": "json_schema",
            "json_schema": {
                "name": "dates",
                "schema": {
                    "type": "object",
                    "properties": {
                        "dates": {
                            "type": "array",
                            "items": {
                                "type": "object",
                                "properties": {
                                    "date": {
                                        "type": "string",
                                        "format": "date",
                                        "description": "The extracted date in ISO 8601 format."
                                    },
                                    "context": {
                                        "type": "string",
                                        "description": "The context or sentence where the date was found."
                                    }
                                },
                                "required": ["date"]
                            }
                        }
                    },
                    "required": ["dates"]
                }
            }
        }
        STR_MODEL;

        $extraction = $connector->extractions('localhost')->extract($model, $document, ['Test section']);

        $mockClient->assertSent(ExtractRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/structured_extract' &&
                $body['doc']['type'] === 'doc' &&
                $body['response_model']['type'] === 'json_schema' &&
                $body['instructions'] === '' &&
                $body['sections'][0] === 'Test section';
        });

        $this->assertCount(2, $extraction->content['dates']);
        $this->assertEquals([
            'date' => '2025-03-22',
            'context' => 'We have some dates 22 march 2025, 15/04/2025 of relevance for the extraction.',
        ], $extraction->content['dates'][0]);

    }

    public function test_extraction_in_specific_section(): void
    {
        $mockClient = MockClient::global([
            ExtractRequest::class => MockResponse::fixture('structured-extract-specific-section'),
        ]);

        $connector = $this->connector($mockClient);

        $documentContent = [
            'type' => 'doc',
            'content' => [
                [
                    'category' => 'page',
                    'attributes' => [
                        'page' => 1,
                    ],
                    'content' => [
                        [
                            'role' => 'body',
                            'text' => 'This document aims to verify the structured extraction pipeline. We have some dates 22 march 2025, 15/04/2025 of relevance for the extraction.',
                            'marks' => [],
                            'attributes' => [
                                'bounding_box' => [],
                                'section' => 'Test section',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $document = new Document('test-structured-extract-id', 'en', $documentContent);

        $model = <<<'STR_MODEL'
        {
            "type": "json_schema",
            "json_schema": {
                "name": "dates",
                "schema": {
                    "type": "object",
                    "properties": {
                        "dates": {
                            "type": "array",
                            "items": {
                                "type": "object",
                                "properties": {
                                    "date": {
                                        "type": "string",
                                        "format": "date",
                                        "description": "The extracted date in ISO 8601 format."
                                    },
                                    "context": {
                                        "type": "string",
                                        "description": "The context or sentence where the date was found."
                                    }
                                },
                                "required": ["date"]
                            }
                        }
                    },
                    "required": ["dates"]
                }
            }
        }
        STR_MODEL;

        $extraction = $connector->extractions('localhost')->extract($model, $document, ['Test section']);

        $mockClient->assertSent(ExtractRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/structured_extract' &&
                $body['doc']['type'] === 'doc' &&
                $body['response_model']['type'] === 'json_schema' &&
                $body['instructions'] === '' &&
                $body['sections'][0] === 'Test section';
        });

        $this->assertCount(2, $extraction->content['dates']);
        $this->assertEquals([
            'date' => '2025-03-22',
            'context' => 'We have some dates 22 march 2025, 15/04/2025 of relevance for the extraction.',
        ], $extraction->content['dates'][0]);
    }

    public function test_document_missing_section_attributes(): void
    {
        $mockClient = MockClient::global([
            ExtractRequest::class => MockResponse::fixture('structured-extract-missing-section-attribute'),
        ]);

        $connector = $this->connector($mockClient);

        $documentContent = [
            'type' => 'doc',
            'content' => [
                [
                    'category' => 'page',
                    'attributes' => [
                        'page' => 1,
                    ],
                    'content' => [
                        [
                            'role' => 'body',
                            'text' => 'This document aims to verify the structured extraction pipeline. We have some dates 22 march 2025, 15/04/2025 of relevance for the extraction.',
                            'marks' => [],
                            'attributes' => [
                                'bounding_box' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $document = new Document('test-structured-extract-id', 'en', $documentContent);

        $model = <<<'STR_MODEL'
        {
            "type": "json_schema",
            "json_schema": {
                "name": "dates",
                "schema": {
                    "type": "object",
                    "properties": {
                        "dates": {
                            "type": "array",
                            "items": {
                                "type": "object",
                                "properties": {
                                    "date": {
                                        "type": "string",
                                        "format": "date",
                                        "description": "The extracted date in ISO 8601 format."
                                    },
                                    "context": {
                                        "type": "string",
                                        "description": "The context or sentence where the date was found."
                                    }
                                },
                                "required": ["date"]
                            }
                        }
                    },
                    "required": ["dates"]
                }
            }
        }
        STR_MODEL;

        $extraction = $connector->extractions('localhost')->extract($model, $document, ['Test section']);

        $mockClient->assertSent(ExtractRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/structured_extract' &&
                $body['doc']['type'] === 'doc' &&
                $body['response_model']['type'] === 'json_schema' &&
                $body['instructions'] === '' &&
                $body['sections'][0] === 'Test section';
        });

        $this->assertCount(2, $extraction->content['dates']);
        $this->assertEquals([
            'date' => '2025-03-22',
            'context' => 'We have some dates 22 march 2025, 15/04/2025 of relevance for the extraction.',
        ], $extraction->content['dates'][0]);
    }

    public function test_extraction_with_instructions(): void
    {
        $mockClient = MockClient::global([
            ExtractRequest::class => MockResponse::fixture('structured-extract-instructions'),
        ]);

        $connector = $this->connector($mockClient);

        $documentContent = [
            'type' => 'doc',
            'content' => [
                [
                    'category' => 'page',
                    'attributes' => [
                        'page' => 1,
                    ],
                    'content' => [
                        [
                            'role' => 'body',
                            'text' => 'This document aims to verify the structured extraction pipeline. We have some dates 22 march 2025, 15/04/2025 of relevance for the extraction.',
                            'marks' => [],
                            'attributes' => [
                                'bounding_box' => [],
                                'section' => 'Test section',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $document = new Document('test-structured-extract-id', 'en', $documentContent);

        $model = <<<'STR_MODEL'
        {
            "type": "json_schema",
            "json_schema": {
                "name": "dates",
                "schema": {
                    "type": "object",
                    "properties": {
                        "dates": {
                            "type": "array",
                            "items": {
                                "type": "object",
                                "properties": {
                                    "date": {
                                        "type": "string",
                                        "format": "date",
                                        "description": "The extracted date in ISO 8601 format."
                                    },
                                    "context": {
                                        "type": "string",
                                        "description": "The context or sentence where the date was found."
                                    }
                                },
                                "required": ["date"]
                            }
                        }
                    },
                    "required": ["dates"]
                }
            }
        }
        STR_MODEL;

        $extraction = $connector->extractions('localhost')->extract($model, $document, instructions: 'Within the context wrap the dates in <ins> tags.');

        $mockClient->assertSent(ExtractRequest::class);

        $mockClient->assertSentCount(1);

        $mockClient->assertSent(function (Request $request) {
            $body = $request->body()->all();

            return $request->resolveEndpoint() === '/library/localhost/structured_extract' &&
                $body['doc']['type'] === 'doc' &&
                $body['response_model']['type'] === 'json_schema' &&
                $body['instructions'] === 'Within the context wrap the dates in <ins> tags.' &&
                is_null($body['sections']);
        });

        $this->assertCount(2, $extraction->content['dates']);
        $this->assertEquals([
            'date' => '2025-03-22',
            'context' => 'We have some dates 22 march 2025, 15/04/2025 of relevance for the extraction.',
        ], $extraction->content['dates'][0]);
    }
}
