<?php

declare(strict_types=1);

namespace Fanmade\RichExceptions;

use Fanmade\RichExceptions\Traits\IsSingleton;

/**
 * @method mixed get(string $key, mixed $default = null)
 * @method void set(string $key, mixed $value)
 * @method bool has(string $key)
 * @method void remove(string $key)
 * @method array all()
 * @method void merge(array $settings)
 * @method void mergeRecursive(array $settings)
 * @method void replace(array $settings)
 * @method void clear()
 */
class Configuration
{
    use IsSingleton;

    private function __construct(private array $settings = [])
    {}

    public static function fromArray(array $array
    ): static {
        if (isset(self::$instance)) {
            self::$instance::replace($array);
        } else {
            self::$instance = new static($array);
        }

        return self::$instance;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::getInstance()->settings[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::getInstance()->settings[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return isset(self::getInstance()->settings[$key]);
    }

    public static function remove(string $key): void
    {
        unset(self::getInstance()->settings[$key]);
    }

    public static function all(): array
    {
        return self::getInstance()->settings;
    }

    public static function merge(array $settings): void
    {
        self::getInstance()->settings = array_merge(self::getInstance()->settings, $settings);
    }

    public static function mergeRecursive(array $settings): void
    {
        self::getInstance()->settings = array_merge_recursive(self::getInstance()->settings, $settings);
    }

    public static function replace(array $settings): void
    {
        self::getInstance()->settings = $settings;
    }

    public static function clear(): void
    {
        self::getInstance()->settings = [];
    }

    public function __get(string $name)
    {
        return self::getInstance()->get($name);
    }

    public function __set(string $name, $value): void
    {
        self::getInstance()->set($name, $value);
    }

    public function __isset(string $name): bool
    {
        return self::getInstance()->has($name);
    }
}
