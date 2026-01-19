<?php

namespace App\Exceptions;

class ConflictException extends ApiException {

    public function __construct(string $message = 'Conflito de estado.', array $details = []) {
        parent::__construct(message: $message, statusCode: 409, errorCode: 'CONFLICT', details: $details);
    }

}