<?php

namespace App\Exceptions;

use RuntimeException;

abstract class ApiException extends RuntimeException {
    protected int $statusCode;
    protected string $errorCode;
    protected array $details;

    public function __construct(string $message, int $statusCode, string $errorCode, array $details = []) {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->details = $details;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function errorCode(): string
    {
        return $this->errorCode;
    }

    public function details(): array
    {
        return $this->details;
    }

}