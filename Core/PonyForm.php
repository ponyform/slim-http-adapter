<?php

namespace PonyForm\Core;

use PonyForm\Core\Http\ControllerFacade;
use PonyForm\Core\Internal\PonyFormOptions;
use PonyForm\Core\Internal\QuestionPluginRegistry;
use PonyForm\PluginContract\PluginInterface;
use PonyForm\PluginContract\QuestionPluginInterface;
use PonyForm\PluginContract\StorePluginInterface;
use Twig;

class PonyForm
{
    public readonly SurveyService $surveyService;
    public readonly ControllerFacade $controller;
    public QuestionPluginRegistry $questionPluginRegistry;
    public readonly PonyFormOptions $options;
    public readonly Twig\Environment $twigEnvironment;
    private readonly Twig\Loader\FilesystemLoader $twigLoader;

    public function __construct(
        private readonly string $baseDir,
        private readonly StorePluginInterface $storePlugin,
        array $options = [],
    ) {
        $this->surveyService = new SurveyService(
            $storePlugin->getSurveyStore(),
            $storePlugin->getSubmissionStore(),
        );
        $this->controller = new ControllerFacade($this);

        $this->questionPluginRegistry = new QuestionPluginRegistry();
        $this->options = PonyFormOptions::create($options);

        $this->initTwig();
    }

    public function registerPlugin(PluginInterface $plugin): void
    {
        if ($plugin instanceof QuestionPluginInterface) {
            $type = $plugin->getType();

            if ($this->questionPluginRegistry->getPlugin($plugin->getType()) === null) {
                $this->questionPluginRegistry->registerPlugin($type, $plugin);

                $templateDirectory = $plugin->getTemplateDirectory();
                if (!empty(strlen($templateDirectory))) {
                    $this->twigLoader->addPath($templateDirectory, "question-plugin-$type");
                }
            }
        }
        if ($plugin instanceof StorePluginInterface) {
        }
    }

    public function getUrlForSurvey(string $id)
    {
        return join('/', [$this->options->pathname, $id]);
    }

    public function getUrlForSurveyThankyou(string $id)
    {
        return join('/', [$this->options->pathname, $id, 'thankyou']);
    }

    private function initTwig()
    {
        $this->twigLoader = new Twig\Loader\FilesystemLoader();
        $this->twigLoader->addPath(
            __DIR__ . DIRECTORY_SEPARATOR . 'templates',
            'core',
        );
        $this->twigEnvironment = new Twig\Environment(
            $this->twigLoader,
            [
                'debug' => $this->options->debug,
            ]
        );
        $this->twigEnvironment->addExtension(new Twig\Extension\DebugExtension());
    }
}
