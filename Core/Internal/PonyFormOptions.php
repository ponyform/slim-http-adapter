<?php

namespace PonyForm\Core\Internal;

use Respect\Validation\Rules;

class PonyFormOptions
{
    private function __construct(
        public readonly bool $debug = false,
        public readonly string $pathname = '/survey',
    ) {
    }

    public static function create(array $options)
    {
        $optionsValidator = new Rules\KeySet(
            new Rules\Key('debug', new Rules\BoolType(), false),
            new Rules\Key('pathname', new Rules\StartsWith('/'), false),
        );
        $optionsValidator->assert($options);

        return new PonyFormOptions(...$options);
    }
}
