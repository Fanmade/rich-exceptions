<?php

namespace Fanmade\RichExceptions\Contracts;

use Fanmade\RichExceptions\Helpers\ContextCollection;

interface HasContext
{
    public function setContext(ContextCollection $context): static;

    public function getContext(): ContextCollection;
}