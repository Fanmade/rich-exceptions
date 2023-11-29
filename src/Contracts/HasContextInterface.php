<?php

namespace Fanmade\RichExceptions\Contracts;

use Fanmade\RichExceptions\Helpers\ContextCollection;

interface HasContextInterface
{
    public function setContext(ContextCollection $context): static;

    public function getContext(): ContextCollection;
}
