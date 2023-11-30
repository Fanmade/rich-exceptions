<?php

declare(strict_types=1);

namespace Fanmade\RichExceptions\Traits;

use Exception;

trait IsSingleton
{
    private static ?self $instance = null;

    public static function getInstance(): static
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead
     */
    private function __construct()
    {}

    /**
     * @codeCoverageIgnore This is just to prevent the instance from being cloned.
     */
    private function __clone()
    {}

    /**
     * @codeCoverageIgnore prevent from being unserialized (which would create a second instance of it).
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
