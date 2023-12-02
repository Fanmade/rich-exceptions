<?php

use Fanmade\RichExceptions\Traits\HasContext;

it(
    'can add context',
    function () {
        $exception = new class('test') extends \Exception {
            use HasContext;
        };

        $exception->addContext('test', 'test');

        expect($exception->getContext()->get('test'))->toBe('test');
    }
);
