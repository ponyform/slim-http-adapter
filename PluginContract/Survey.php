<?php

namespace PonyForm\PluginContract;

class Survey
{
    /**
     * @param Question[] $questions
     */
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $secret,
        public readonly array $questions,
    ) {
    }
}
