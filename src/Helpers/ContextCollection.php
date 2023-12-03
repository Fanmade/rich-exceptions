<?php

declare(strict_types=1);

namespace Fanmade\RichExceptions\Helpers;

use Fanmade\RichExceptions\Traits\HasRichExceptionsConfig;
use InvalidArgumentException;
use Iterator;
use JsonException;
use function is_array;

/**
 * The context can contain various values, while it is either keyed by integers or strings.
 * This class is meant to provide some helper methods to make working with the context
 * easier. It is not meant to be used outside the RichExceptions package.
 *
 * @internal
 */
class ContextCollection implements Iterator
{
    use HasRichExceptionsConfig;

    private int $position = 0;

    private array $keys;

    public function __construct(private array $context = [])
    {
        $this->keys = array_keys($context);
    }

    public static function make(array $context): static
    {
        return new static($context);
    }

    public static function fromArray(array $array): static
    {
        return new static($array);
    }

    public function toJson(): string
    {
        try {
            return json_encode($this->context, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $message = $e->getMessage();
            $message = str_replace('"', '\"', $message);

            return '{"error": "Could not encode context to JSON.", "message": "' . $message . '."}';
        }
    }

    public function findNumericKey(string $key): int|false
    {
        return array_search($key, $this->keys, true);
    }

    public function getKeyForPosition(int $position = null): string|int
    {
        $key = $this->findKeyForPosition($position);
        if (!$key) {
            throw new InvalidArgumentException("Position '$position' does not exist in context.");
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

    public function set(string $key, mixed $content): static
    {
        $this->context[$key] = $content;
        $this->keys[] = $key;

        return $this;
    }

    /**
     * Add a new value to the context. If the key does not yet exist, the content will be added as-is.
     * If the key already exists, it will check if the value is an array. If it is, the
     * content will be appended to it. If it is not, the existing value will be
     * converted to an array and then the content will be appended to it.
     *
     * @param string $key
     * @param mixed $content
     * @return $this
     */
    public function add(string $key, mixed $content): static
    {
        if (!isset($this->context[$key])) {
            $this->context[$key] = $content;
            $this->keys[] = $key;

            return $this;
        }
        if (!is_array($this->context[$key])) {
            $this->context[$key] = [$this->context[$key]];
        }
        $this->context[$key][] = $content;

        return $this;
    }

    public function remove(string $key): static
    {
        $index = $this->findNumericKey($key);
        if ($index !== false) {
            unset($this->context[$key], $this->keys[$index]);
        }

        return $this;
    }

    public function get(string $string, mixed $default = null): mixed
    {
        return $this->context[$string] ?? $default;
    }

    public function only(array $array): array
    {
        $result = [];
        foreach ($array as $key) {
            $result[$key] = $this->get($key);
        }

        return $result;
    }

    public function all(): array
    {
        return $this->context;
    }

    public function has(string $key): bool
    {
        return isset($this->context[$key]);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
