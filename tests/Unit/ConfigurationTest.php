<?php

use Fanmade\RichExceptions\Configuration;

test(
    'a configuration class can be created from an array',
    function () {
        $sut = Configuration::fromArray(['foo' => 'bar']);

        expect($sut)
            ->toBeInstanceOf(Configuration::class)
            ->and($sut->get('foo'))->toBe('bar');
    }
);

it(
    'can get a value from the configuration via singleton',
    function () {
        Configuration::fromArray(['foo' => 'bar']);

        expect(Configuration::getInstance()->get('foo'))->toBe('bar');
    }
);

it(
    'can set a value in the configuration via singleton',
    function () {
        Configuration::fromArray(['foo' => 'bar']);

        Configuration::getInstance()->set('foo', 'baz');

        expect(Configuration::getInstance()->get('foo'))->toBe('baz');
    }
);

test(
    'methods can be called statically',
    function () {
        Configuration::fromArray(['foo' => 'bar']);

        expect(Configuration::get('foo'))->toBe('bar');
    }
);

test(
    'methods can be called dynamically',
    function () {
        Configuration::fromArray(['foo' => 'bar']);

        expect(Configuration::getInstance()->foo)->toBe('bar');
    }
);

it(
    'can set values dynamically',
    function () {
        Configuration::fromArray(['foo' => 'bar']);

        Configuration::getInstance()->foo = 'baz';

        expect(Configuration::getInstance()->foo)->toBe('baz');
    }
);

it(
    'will check for dynamic values',
    function () {
        Configuration::fromArray(['foo' => 'bar']);

        expect(isset(Configuration::getInstance()->foo))->toBeTrue();
    }
);

it(
    'allows for the static methods to be called non-static',
    function () {
        Configuration::fromArray(['foo' => 'bar']);

        expect(Configuration::getInstance()->get('foo'))->toBe('bar');
    }
);

it(
    'can return all values',
    function () {
        Configuration::fromArray(['foo' => 'bar', 'baz' => 'qux']);

        expect(Configuration::getInstance()->all())->toBe(['foo' => 'bar', 'baz' => 'qux']);
    }
);

it(
    'can merge values',
    function () {
        Configuration::fromArray(['foo' => 'bar']);

        Configuration::getInstance()->merge(['baz' => 'qux']);

        expect(Configuration::getInstance()->all())->toBe(['foo' => 'bar', 'baz' => 'qux']);
    }
);

it(
    'can merge values recursively',
    function () {
        Configuration::fromArray(['foo' => ['bar' => 'baz']]);

        Configuration::getInstance()->mergeRecursive(['foo' => ['baz' => 'qux']]);

        expect(Configuration::getInstance()->all())->toBe(['foo' => ['bar' => 'baz', 'baz' => 'qux']]);
    }
);

it(
    'can replace values',
    function () {
        Configuration::fromArray(['foo' => 'bar']);

        Configuration::getInstance()->replace(['baz' => 'qux']);

        expect(Configuration::getInstance()->all())->toBe(['baz' => 'qux']);
    }
);

it(
    'can clear all values',
    function () {
        Configuration::fromArray(['foo' => 'bar']);

        Configuration::getInstance()->clear();

        expect(Configuration::getInstance()->all())->toBe([]);
    }
);


it(
    'can remove specific values',
    function () {
        Configuration::fromArray(['foo' => 'bar', 'baz' => 'qux']);

        Configuration::getInstance()->remove('foo');

        expect(Configuration::getInstance()->all())->toBe(['baz' => 'qux']);
    }
);
