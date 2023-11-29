<?php

declare(strict_types=1);

namespace Fanmade\RichExceptions\Exceptions;

use Exception;
use Fanmade\RichExceptions\Contracts\HasContextInterface;
use Fanmade\RichExceptions\Contracts\RichExceptionInterface;
use Fanmade\RichExceptions\Helpers\ContextCollection;
use Fanmade\RichExceptions\Traits\HasContext;
use Throwable;

class RichException extends Exception implements RichExceptionInterface, HasContextInterface
{
    use HasContext;

    private ?Throwable $originalException;

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->initContext();
        parent::__construct($message, $code, $previous);
    }

    public function setOriginalException(Throwable $originalException): static
    {
        $this->originalException = $originalException;

        return $this;
    }

    public static function from(Throwable $exception, array|ContextCollection $context = []): static
    {
        // This allows you to just always call from() without having to check if the exception is already a RichException.
        if ($exception instanceof static) {
            return $exception;
        }
        $context = $context instanceof ContextCollection ? $context : new ContextCollection($context);

        $richException = (new static($exception->getMessage(), (int) $exception->getCode(), $exception))
            ->setOriginalException($exception)
            ->setContext($context);
        $richException->file = $exception->getFile();
        $richException->line = $exception->getLine();

        return $richException;
    }

    public function getOriginalException(): ?Throwable
    {
        return $this->originalException;
    }

    public function getDefaultErrorMessage(): string
    {
        return 'Error';
    }

    public function __toString(): string
    {
        return $this->originalException?->getMessage() ?? $this->getDefaultErrorMessage();
    }

    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            // The normal trace as an array is too big for the log.
            'trace' => $this->getTraceAsString(),
            'context' => $this->context,
        ];
    }
}
