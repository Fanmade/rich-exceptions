<?php

use Fanmade\RichExceptions\Contracts\RichExceptionInterface;
use Fanmade\RichExceptions\Exceptions\RichException;
use Fanmade\RichExceptions\Helpers\ContextCollection;

test('a context collection can be created from an array', function () {
    $sut = ContextCollection::fromArray(['foo' => 'bar']);
    expect($sut)->toBeInstanceOf(ContextCollection::class);
});

test('a context collection can be iterated over', function () {
    $sut = ContextCollection::fromArray(['foo' => 'bar', 'baz' => 'qux']);
    $result = [];
    foreach ($sut as $key => $value) {
        $result[$key] = $value;
    }
    expect($result)->toBe(['foo' => 'bar', 'baz' => 'qux']);
});