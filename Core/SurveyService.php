<?php

namespace PonyForm\Core;

use Exception;
use PonyForm\PluginContract\SurveyStoreInterface;
use PonyForm\Core\Exception\StoreAccessException;
use PonyForm\PluginContract\SubmissionStoreInterface;
use PonyForm\PluginContract\Survey;

class SurveyService
{
    public function __construct(
        private readonly SurveyStoreInterface $surveyStore,
        private readonly SubmissionStoreInterface $submissionStore,
    ) {
    }

    public function getSurveyById(string $id): Survey | null
    {
        try {
            $survey = $this->surveyStore->readSurveyById($id);
        } catch (Exception $e) {
            throw new StoreAccessException('Internal Error while reading survey from database', 0, $e);
        }

        return $survey;
    }

    public function submitResponse(string $surveyId, array $replies): void
    {
        try {
            $this->submissionStore->createSubmission($surveyId, $replies);
        } catch (Exception $e) {
            throw new StoreAccessException('Internal Error while writing submission to database', 0, $e);
        }
    }
}
