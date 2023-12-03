<?php

use Fanmade\RichExceptions\RichExceptionsConfig;

test(
    'a configuration class can be created from an array',
    function () {
        $sut = RichExceptionsConfig::fromArray(['foo' => 'bar']);

        expect($sut)
            ->toBeInstanceOf(RichExceptionsConfig::class)
            ->and($sut::get('foo'))->toBe('bar');
    }
);

it(
    'can get a value from the configuration via singleton',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar']);

        expect(RichExceptionsConfig::getInstance()->get('foo'))->toBe('bar');
    }
);

it(
    'can set a value in the configuration via singleton',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar']);

        RichExceptionsConfig::getInstance()->set('foo', 'baz');

        expect(RichExceptionsConfig::getInstance()->get('foo'))->toBe('baz');
    }
);

test(
    'methods can be called statically',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar']);

        expect(RichExceptionsConfig::get('foo'))->toBe('bar');
    }
);

test(
    'methods can be called dynamically',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar']);

        expect(RichExceptionsConfig::getInstance()->foo)->toBe('bar');
    }
);

it(
    'can set values dynamically',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar']);

        RichExceptionsConfig::getInstance()->foo = 'baz';

        expect(RichExceptionsConfig::getInstance()->foo)->toBe('baz');
    }
);

it(
    'will check for dynamic values',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar']);

        expect(isset(RichExceptionsConfig::getInstance()->foo))->toBeTrue();
    }
);

it(
    'allows for the static methods to be called non-static',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar']);

        expect(RichExceptionsConfig::getInstance()->get('foo'))->toBe('bar');
    }
);

it(
    'can return all values',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar', 'baz' => 'qux']);

        expect(RichExceptionsConfig::getInstance()->all())->toBe(['foo' => 'bar', 'baz' => 'qux']);
    }
);

it(
    'can merge values',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar']);

        RichExceptionsConfig::getInstance()->merge(['baz' => 'qux']);

        expect(RichExceptionsConfig::getInstance()->all())->toBe(['foo' => 'bar', 'baz' => 'qux']);
    }
);

it(
    'can merge values recursively',
    function () {
        RichExceptionsConfig::fromArray(['foo' => ['bar' => 'baz']]);

        RichExceptionsConfig::getInstance()->mergeRecursive(['foo' => ['baz' => 'qux']]);

        expect(RichExceptionsConfig::getInstance()->all())->toBe(['foo' => ['bar' => 'baz', 'baz' => 'qux']]);
    }
);

it(
    'can replace values',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar']);

        RichExceptionsConfig::getInstance()->replace(['baz' => 'qux']);

        expect(RichExceptionsConfig::getInstance()->all())->toBe(['baz' => 'qux']);
    }
);

it(
    'can clear all values',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar']);

        RichExceptionsConfig::getInstance()->clear();

        expect(RichExceptionsConfig::getInstance()->all())->toBe([]);
    }
);


it(
    'can remove specific values',
    function () {
        RichExceptionsConfig::fromArray(['foo' => 'bar', 'baz' => 'qux']);

        RichExceptionsConfig::getInstance()->remove('foo');

        expect(RichExceptionsConfig::getInstance()->all())->toBe(['baz' => 'qux']);
    }
);

it(
    'will load the default configuration by default',
    function () {
        RichExceptionsConfig::getInstance()->clear();
        expect(RichExceptionsConfig::initialize()->all())->toBe(include __DIR__ . '/../../config.php');
    }
);

it(
    'can load a configuration file',
    function () {
        RichExceptionsConfig::getInstance()->clear();
        $path = __DIR__ . '/../data/testConfig.php';
        RichExceptionsConfig::loadConfig($path);
        expect(RichExceptionsConfig::getInstance()->all())->toBe(include $path);
    }
);

it(
    'will throw an exception if the config file does not exist',
    function () {
        RichExceptionsConfig::getInstance()->clear();
        $path = '/../data/doesNotExist.php';
        RichExceptionsConfig::loadConfig($path);
    }
)->throws(RuntimeException::class, "Config file not found at '/../data/doesNotExist.php'.");

it(
    'will throw an exception if the config file does not return an array',
    function () {
        RichExceptionsConfig::getInstance()->clear();
        $path = __DIR__ . '/../data/invalidTestConfig.php';
        RichExceptionsConfig::loadConfig($path);
    }
)->throws(RuntimeException::class);

it(
    'can be initialized',
    function () {
        resetSingleton(RichExceptionsConfig::getInstance());
        expect(RichExceptionsConfig::loadConfig()->all())->toBe(include __DIR__ . '/../../config.php');
    }
);
