<?php

use Fanmade\RichExceptions\Contracts\RichExceptionInterface;
use Fanmade\RichExceptions\Exceptions\RichException;
use Fanmade\RichExceptions\Helpers\ContextCollection;

test(
    'a rich exception can be created from a basic exception',
    function () {
        $sut = RichException::from(new Exception('foo'));
        expect($sut)->toBeInstanceOf(RichExceptionInterface::class);
    }
);

test(
    'the original exception can be retrieved from a rich exception',
    function () {
        $originalException = new Exception('foo');
        $sut = RichException::from($originalException);
        expect($sut->getOriginalException())->toBe($originalException)
            ->and($sut->getOriginalException()?->getMessage())
            ->toBe('foo');
    }
);

it(
    'can be converted to a string',
    function () {
        $sut = RichException::from(new Exception('foo'));
        expect((string) $sut)->toBe('foo');
    }
);

it(
    'provides a default error message',
    function () {
        $sut = RichException::from(new Exception());
        expect($sut->getDefaultErrorMessage())->toBe('Error');
    }
);

test(
    'the default error message can be overriden',
    function () {
        $sut = new class extends RichException {
            public function getDefaultErrorMessage(): string
            {
                return 'foo';
            }
        };
        expect($sut->getDefaultErrorMessage())->toBe('foo');
    }
);

it(
    'can be converted to an array',
    function () {
        $sut = RichException::from(new Exception('foo', 42, new Exception()), ['bar' => 'baz']);
        $result = $sut->toArray();

        expect($result)->toHaveKeys(['message', 'code', 'file', 'line', 'context'])
            ->and($result['message'])->toBe('foo')
            ->and($result['code'])->toBe(42)
            ->and($result['file'])->toBe(__FILE__)
            ->and($result['line'])->toBeGreaterThan(0)
            ->and($result['context'])->toBeInstanceOf(ContextCollection::class)
            ->and($result['context']->toArray())->toBe(['bar' => 'baz']);
    }
);

it(
    'will return itself when calling from() on a rich exception',
    function () {
        $sut = RichException::from(new Exception('foo'));
        expect(RichException::from($sut))->toBe($sut);
    }
);
