<?php

namespace PonyForm\Core\Internal;

use Hidehalo\Nanoid\Client;

class RandomGenerator
{
    // see https://github.com/CyberAP/nanoid-dictionary#nolookalikessafe
    const NO_LOOKALIKES = '6789BCDFGHJKLMNPQRTWbcdfghjkmnpqrtwz';
    const FULL_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz-';

    private Client $nanoIdClient;

    protected function __construct()
    {
        $this->nanoIdClient = new Client();
    }

    public function generateSurveyId()
    {
        return $this->nanoIdClient->formattedId(self::NO_LOOKALIKES, 24);
    }

    public function generateSurveySecret()
    {
        return $this->nanoIdClient->formattedId(self::FULL_ALPHABET, 32);
    }
}
