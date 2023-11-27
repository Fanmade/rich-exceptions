<?php

declare(strict_types=1);

namespace Fanmade\RichExceptions\Exceptions;

use Fanmade\RichExceptions\Contracts\RichExceptionInterface;
use Fanmade\RichExceptions\Helpers\ContextCollection;
use Throwable;

class RichException implements RichExceptionInterface
{
    public function __construct(
        private readonly ?Throwable $originalException = null,
        private readonly ContextCollection $context = new ContextCollection()
    ) {}

    public static function from(Throwable $exception): static
    {
        return new static($exception);
    }

    public function getOriginalException(): ?Throwable
    {
        return $this->originalException;
    }

    public function getContext(): ContextCollection
    {
        return $this->context;
    }
}