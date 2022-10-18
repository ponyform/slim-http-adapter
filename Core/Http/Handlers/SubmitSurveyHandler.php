<?php

namespace PonyForm\Core\Http\Handlers;

use PonyForm\Core\PonyForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class SubmitSurveyHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly PonyForm $ponyForm,
        private readonly string $surveyId
    ) {
    }

    public function handle(ServerRequestInterface $req): ResponseInterface
    {
        $fields = $this->getSubmissionFieldsFromRequestBody($req->getParsedBody());

        $this->ponyForm->surveyService->submitResponse($this->surveyId, $fields);

        $res = new Response();

        return $res
            ->withStatus(302)
            ->withHeader("Location", $this->ponyForm->getUrlForSurveyThankyou($this->surveyId));
    }

    private function getSubmissionFieldsFromRequestBody(array $requestBody)
    {
        $fields = [];
        foreach ($requestBody as $key => $value) {
            array_push($fields, [
                "id" => $key,
                "value" => $value,
            ]);
        }
        return $fields;
    }
}
