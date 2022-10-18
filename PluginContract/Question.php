<?php

namespace PonyForm\PluginContract;

class Question
{
    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly string $question,
        public readonly bool $required,
        public readonly ?array $extra,
    ) {
    }
}
