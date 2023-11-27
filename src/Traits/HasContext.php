<?php

declare(strict_types=1);

namespace Fanmade\RichExceptions\Traits;

use Fanmade\RichExceptions\Helpers\ContextCollection;

trait HasContext
{
    private ContextCollection $context;

    private function initContext(array $context = []): void
    {
        $this->context = new ContextCollection($context);
    }

    public function addContext(string $key, mixed $content): static
    {
        $this->context->add($key, $content);

        return $this;
    }

    public function setContext(ContextCollection $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getContext(): ContextCollection
    {
        return $this->context;
    }
}