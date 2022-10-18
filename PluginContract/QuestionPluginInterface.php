<?php

namespace PonyForm\PluginContract;

interface QuestionPluginInterface extends PluginInterface
{
    public function getType(): string;

    public function getTemplateDirectory(): string;
    public function getTemplateFilename(): string;

    public function getStyleSheet(): string;
}
