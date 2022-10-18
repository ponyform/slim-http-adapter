<?php

namespace PonyForm\Core\Http\Handlers;

use PonyForm\Core\PonyForm;
use PonyForm\PluginContract\QuestionPluginInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Twig\TemplateWrapper;

class ViewSurveyHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly PonyForm $ponyForm,
        private readonly string $surveyId,
    ) {
    }

    public function handle(ServerRequestInterface $req): ResponseInterface
    {
        $res = new Response();

        $survey = $this->ponyForm->surveyService->getSurveyById($this->surveyId);
        $surveyInfo = get_object_vars($survey);
        unset($surveyInfo['questions']);

        $stylesheets = [];
        $questions = [];
        foreach ($survey->questions as $question) {
            $plugin = $this->ponyForm->questionPluginRegistry->getPlugin($question->type);
            if ($plugin === null) {
                // Error: No plugin is installed that could render that question type
                continue;
            }

            $inputAttributes = $this->getHtmlAttributes([
                'name' => $question->id,
                "data-type" => $question->type,
                'data-required' => $question->required,
            ]);

            $renderedQuestion = $this
                ->resolvePluginTemplate($plugin, $plugin->getTemplateFilename())
                ->render(
                    [
                        "survey" => $surveyInfo,
                        "question" => $question,
                        'inputAttributes' => $inputAttributes,
                    ]
                );

            if (!str_contains($renderedQuestion, $inputAttributes)) {
                // Error: the input attributes were not rendered
                continue;
            }

            $questionInfo = get_object_vars($question);
            unset($questionInfo['extra']);
            $questionInfo['html'] = trim($renderedQuestion);
            $questions[] = $questionInfo;

            if (empty($stylesheets[$plugin->getType()])) {
                $stylesheets[$plugin->getType()] = $this
                    ->resolvePluginTemplate($plugin, $plugin->getStyleSheet())
                    ->render();
            }
        }

        $html = $this->ponyForm->twigEnvironment->render(
            '@core/survey.twig',
            [
                'submitUrl' => $this->ponyForm->getUrlForSurvey($this->surveyId),
                'survey' => $surveyInfo,
                'questions' => $questions,
                'stylesheets' => array_values($stylesheets),
            ]
        );

        $res->getBody()->write($html);

        return $res;
    }

    private function resolvePluginTemplate(QuestionPluginInterface $plugin, string $filename): TemplateWrapper
    {
        return $this->ponyForm->twigEnvironment->resolveTemplate(
            "@question-plugin-{$plugin->getType()}/{$filename}",
        );
    }

    private function getHtmlAttributes(array $attributes)
    {
        return join(' ', array_map(function ($key) use ($attributes) {
            $value = $attributes[$key];
            if (is_bool($value)) {
                return $value ? $key : '';
            }
            return "$key=\"$value\"";
        }, array_keys($attributes)));
    }
}
