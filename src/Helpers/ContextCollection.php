<?php

declare(strict_types=1);

namespace Fanmade\RichExceptions\Helpers;

use InvalidArgumentException;
use Iterator;

/**
 * The context can contain various values, while it is either keyed by integers or strings. This class is meant to
 * provide some helper methods to make working with the context easier. It is not meant to be used outside of the
 * RichExceptions package.
 *
 * @internal
 */
class ContextCollection implements Iterator
{
    private int $position = 0;

    private array $keys;

    public function __construct(private readonly array $context = [])
    {
        $this->keys = array_keys($context);
    }

    public static function make(array $context): static
    {
        return new static($context);
    }

    public static function fromArray(array $array): static {
        return new static($array);
    }

    public function getNumericKey(string $key): int
    {
        if (!in_array($key, $this->keys, true)) {
            throw new InvalidArgumentException("Key {$key} does not exist in context.");
        }
        return array_search($key, $this->keys, true);
    }

    public function getKeyForPosition(int $position = null): string|int
    {
        $key = $this->findKeyForPosition($position );
        if (!$key) {
            throw new InvalidArgumentException("Position $position does not exist in context.");
        }
        return $key;
    }

    public function findKeyForPosition(int $position = null): string|int|false
    {
        if ($position === null) {
            $position = $this->position;
        }
        return $this->keys[$position] ?? false;
    }

    public function current(): mixed
    {
        return $this->context[$this->getKeyForPosition($this->position)];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int|string
    {
        return $this->findKeyForPosition($this->position) ?? $this->position;
    }

    public function valid(): bool
    {
        $key = $this->findKeyForPosition();
        if (!$key) {
            return false;
        }

        return isset($this->context[$key]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function toArray(): array
    {
        return $this->context;
    }
}