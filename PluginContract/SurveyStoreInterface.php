<?php

namespace PonyForm\PluginContract;

interface SurveyStoreInterface
{
    public function readSurveyById(string $id): Survey | null;
}
