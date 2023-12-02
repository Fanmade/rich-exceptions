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

it(
    'can be created statically',
    function () {
        $sut = ContextCollection::make(['foo' => 'bar']);
        expect($sut)->toBeInstanceOf(ContextCollection::class)->and($sut->toArray())->toBe(['foo' => 'bar']);
    }
);

test(
    'specific keys can be set',
    function () {
        $sut = ContextCollection::make(['foo' => 'bar']);
        $sut->set('moo', 'mar');
        expect($sut->toArray())->toBe(['foo' => 'bar', 'moo' => 'mar']);
    }
);

it(
    'can override specific keys',
    function () {
        $sut = ContextCollection::make(['foo' => 'bar', 'moo' => 'maz']);
        $sut->set('foo', 'mar');
        expect($sut->toArray())->toBe(['foo' => 'mar', 'moo' => 'maz']);
    }
);


it(
    'can return all values',
    function () {
        $sut = ContextCollection::make(['foo' => 'bar', 'moo' => 'maz']);
        expect($sut->all())->toBe(['foo' => 'bar', 'moo' => 'maz']);
    }
);

it(
    'can be converted to json',
    function () {
        $sut = ContextCollection::make(['foo' => 'bar', 'moo' => 'maz']);
        expect($sut->toJson())->toBe('{"foo":"bar","moo":"maz"}');
    }
);

it(
    'can be converted to string',
    function () {
        $sut = ContextCollection::make(['foo' => 'bar', 'moo' => 'maz']);
        expect((string) $sut)->toBe('{"foo":"bar","moo":"maz"}');
    }
);

it(
    'can check if a key exists',
    function () {
        $sut = ContextCollection::make(['foo' => 'bar', 'moo' => 'maz']);
        expect($sut->has('foo'))->toBeTrue()->and($sut->has('unknown'))->toBeFalse();
    }
);

it(
    'will catch errors when encoding to json',
    function () {
        $sut = ContextCollection::make(['foo' => 'bar', 'moo' => "\xB1\x31"]);

        $result = $sut->toJson();
        expect($result)->toBeJson()
            ->and(json_decode($sut->toJson(), true))
            ->toHaveKeys(['error', 'message']);
    }
);

it(
    'will throw an error when trying to get a key that does not exist',
    function () {
        $sut = ContextCollection::make(['foo' => 'bar', 'moo' => 'maz']);
        $sut->getKeyForPosition(999);
    }
)->throws(InvalidArgumentException::class, 'Position \'999\' does not exist in context.');

it(
    'will accept multiple values for a single key',
    function () {
        $sut = ContextCollection::make(['foo' => 'bar', 'moo' => 'maz']);
        $sut->add('foo', 'mar');
        expect($sut->toArray())->toBe(['foo' => ['bar', 'mar'], 'moo' => 'maz']);
    }
);
