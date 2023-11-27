<?php

namespace Fanmade\RichExceptions\Contracts;

use Throwable;

interface RichException
{
    public static function from(Throwable $exception): self;

    public function getOriginalException(): ?Throwable;
}