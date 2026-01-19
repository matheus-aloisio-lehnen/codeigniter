<?php

namespace App\Exceptions;

class ForbiddenException extends ApiException {

    public function __construct(string $message = 'Acesso negado.', array $details = []) {
        parent::__construct(message: $message, statusCode: 403, errorCode: 'FORBIDDEN', details: $details);
    }

}