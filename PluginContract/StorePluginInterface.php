<?php

namespace PonyForm\PluginContract;

use PonyForm\PluginContract\SubmissionStoreInterface;
use PonyForm\PluginContract\SurveyStoreInterface;

interface StorePluginInterface extends PluginInterface
{
    public function getSurveyStore(): SurveyStoreInterface;
    public function getSubmissionStore(): SubmissionStoreInterface;
}
