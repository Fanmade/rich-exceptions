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
        $this->getContext()->add($key, $content);

        return $this;
    }

    public function setContext(ContextCollection $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getContext(): ContextCollection
    {
        if (!isset($this->context)) {
            $this->initContext();
        }

        return $this->context;
    }

    public function getContextArray(): array
    {
        return $this->getContext()->all();
    }

    public function setContextFromArray(array $context): static
    {
        $this->setContext(new ContextCollection($context));

        return $this;
    }
}
