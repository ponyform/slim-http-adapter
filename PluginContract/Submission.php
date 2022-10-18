<?php

namespace PonyForm\PluginContract;

class Submission
{
    public function __construct(
        public readonly int $id,
        public readonly string $surveyId,
        public readonly array $replies,
    ) {
    }
}
