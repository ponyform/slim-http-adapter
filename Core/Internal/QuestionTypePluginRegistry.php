<?php

namespace PonyForm\Core\Internal;

use PonyForm\PluginContract\QuestionPluginInterface;

class QuestionPluginRegistry
{
    private array $plugins = [];

    public function registerPlugin(string $type, QuestionPluginInterface $plugin)
    {
        $this->plugins[$type] = $plugin;
    }

    public function getPlugin(string $type): QuestionPluginInterface | null
    {
        return $this->plugins[$type] ?? null;
    }
}
