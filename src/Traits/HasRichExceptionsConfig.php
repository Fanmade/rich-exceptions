<?php

declare(strict_types=1);

namespace Fanmade\RichExceptions\Traits;

use Fanmade\RichExceptions\RichExceptionsConfig;

trait HasRichExceptionsConfig
{
    private RichExceptionsConfig $config;

    public function setConfig(RichExceptionsConfig $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function getConfig(): RichExceptionsConfig
    {
        if (!isset($this->config)) {
            $this->config = RichExceptionsConfig::getInstance();
        }

        return $this->config;
    }
}
