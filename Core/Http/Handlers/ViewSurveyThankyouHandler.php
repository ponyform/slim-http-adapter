<?php

namespace PonyForm\Core\Http\Handlers;

use PonyForm\Core\PonyForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class ViewSurveyThankyouHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly PonyForm $ponyForm,
        private readonly string $surveyId,
    ) {
    }

    public function handle(ServerRequestInterface $req): ResponseInterface
    {
        $res = new Response();

        $res->getBody()->write("Thank you for your entry!");

        return $res;
    }
}
