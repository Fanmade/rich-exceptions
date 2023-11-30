<?php
/**
 * @noinspection PhpIllegalPsrClassPathInspection
 * @noinspection PhpMultipleClassesDeclarationsInOneFile
 */

use Fanmade\RichExceptions\Traits\IsSingleton;

class SingletonClassA
{
    use IsSingleton;

    public int $foo = 0;
}

class SingletonClassB
{
    use IsSingleton;

    public int $foo = 1;
}

test(
    'it will always return the same instance for the same class',
    function () {
        $sut = SingletonClassA::getInstance();
        $sut2 = SingletonClassA::getInstance();

        expect($sut)->toBe($sut2);
    }
);

test(
    'different singleton classes will not collide',
    function () {
        $sut = SingletonClassA::getInstance();
        $sut2 = SingletonClassB::getInstance();

        expect($sut)->not->toBe($sut2)
            ->and($sut->foo)->not->toBe($sut2->foo);
    }
);
