<?php

namespace Fanmade\RichExceptions\Contracts;

use Throwable;

interface RichExceptionInterface
{
    public static function from(Throwable $exception): self;

    public function getOriginalException(): ?Throwable;
}
