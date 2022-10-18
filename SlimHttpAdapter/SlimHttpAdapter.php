<?php

namespace PonyForm\SlimHttpAdapter;

use PonyForm\Core\PonyForm;
use Slim\App;

class SlimHttpAdapter
{
    public function __construct(
        private readonly PonyForm $ponyForm,
    ) {
    }

    public function register(App $slimApp)
    {
        $controller = $this->ponyForm->controller;

        $slimApp->get(
            $this->ponyForm->getUrlForSurvey('{id}'),
            fn ($req, $res, $args) => $controller->viewSurvey($req, $args['id'])
        );
        $slimApp->post(
            $this->ponyForm->getUrlForSurvey('{id}'),
            fn ($req, $res, $args) => $controller->submitSurvey($req, $args['id'])
        );
        $slimApp->get(
            $this->ponyForm->getUrlForSurveyThankyou('{id}'),
            fn ($req, $res, $args) => $controller->viewSurveyThankyou($req, $args['id'])
        );
    }
}
