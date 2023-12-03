<?php


use Fanmade\RichExceptions\RichExceptionsConfig;
use Fanmade\RichExceptions\Traits\HasRichExceptionsConfig;

it(
    'can set and get config',
    function () {
        $exception = new class('test') extends Exception {
            use HasRichExceptionsConfig;
        };

        $config = RichExceptionsConfig::getInstance();

        $exception->setConfig($config);

        expect($exception->getConfig())->toBe($config);
    }
);

it(
    'uses the default config if none is set',
    function () {
        $exception = new class('test') extends Exception {
            use HasRichExceptionsConfig;
        };

        expect($exception->getConfig())->toBe(RichExceptionsConfig::getInstance());
    }
);
