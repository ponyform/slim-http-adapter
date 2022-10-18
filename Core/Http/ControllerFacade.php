<?php

namespace PonyForm\Core\Http;

use PonyForm\Core\PonyForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ControllerFacade
{
    public function __construct(
        private readonly PonyForm $ponyForm
    ) {
    }

    public function viewSurvey(ServerRequestInterface $req, string $surveyId): ResponseInterface
    {
        return (new Handlers\ViewSurveyHandler($this->ponyForm, $surveyId))->handle($req);
    }

    public function submitSurvey(ServerRequestInterface $req, string $surveyId): ResponseInterface
    {
        return (new Handlers\SubmitSurveyHandler($this->ponyForm, $surveyId))->handle($req);
    }

    public function viewSurveyThankyou(ServerRequestInterface $req, string $surveyId): ResponseInterface
    {
        return (new Handlers\ViewSurveyThankyouHandler($this->ponyForm, $surveyId))->handle($req);
    }
}
