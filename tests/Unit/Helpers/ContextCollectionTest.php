<?php

use Fanmade\RichExceptions\Helpers\ContextCollection;

test(
    'a context collection can be created from an array',
    function () {
        $sut = ContextCollection::fromArray(['foo' => 'bar']);
        expect($sut)->toBeInstanceOf(ContextCollection::class);
    }
);

test(
    'a context collection can be iterated over',
    function () {
        $sut = ContextCollection::fromArray(['foo' => 'bar', 'baz' => 'qux']);
        $result = [];
        foreach ($sut as $key => $value) {
            $result[$key] = $value;
        }
        expect($result)->toBe(['foo' => 'bar', 'baz' => 'qux']);
    }
);

it(
    'accepts new values',
    function () {
        $sut = ContextCollection::fromArray(['foo' => 'bar']);
        $sut->add('baz', 'qux');
        expect($sut->toArray())->toBe(['foo' => 'bar', 'baz' => 'qux']);
    }
);

it(
    'can remove items from the collection',
    function () {
        $sut = ContextCollection::fromArray(['foo' => 'bar', 'baz' => 'qux']);
        $sut->remove('foo');
        expect($sut->toArray())->toBe(['baz' => 'qux']);
    }
);

it(
    'will not throw an error when removing unknown keys',
    function () {
        $sut = ContextCollection::fromArray(['foo' => 'bar', 'baz' => 'qux']);
        $sut->remove('unknown');
        expect($sut->toArray())->toBe(['foo' => 'bar', 'baz' => 'qux']);
    }
);

it(
    'returns single values by key',
    function () {
        $sut = ContextCollection::fromArray(['foo' => 'bar', 'baz' => 'qux']);
        expect($sut->get('foo'))->toBe('bar');
    }
);

it(
    'returns null when a key does not exist',
    function () {
        $sut = ContextCollection::fromArray(['foo' => 'bar', 'baz' => 'qux']);
        expect($sut->get('unknown'))->toBeNull();
    }
);

it(
    'can return an optional default value when a key does not exist',
    function () {
        $sut = ContextCollection::fromArray(['foo' => 'bar', 'baz' => 'qux']);
        expect($sut->get('unknown', 'default'))->toBe('default');
    }
);

it(
    'can return a list of specific keys',
    function () {
        $sut = ContextCollection::fromArray(
            ['foo' => 'bar', 'baz' => 'qux', 'mar' => 'maz']
        );
        expect($sut->only(['foo', 'mar', 'empty']))->toBe(['foo' => 'bar', 'mar' => 'maz', 'empty' => null]);
    }
);
