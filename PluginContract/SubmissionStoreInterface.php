<?php

namespace PonyForm\PluginContract;

use PonyForm\PluginContract\Submission;

interface SubmissionStoreInterface
{
    /**
     * @return string the ID of the new created submission
     */
    public function createSubmission(string $surveyId, array $replies): string;
}
