<?php

namespace App\Exceptions;

class NotFoundException extends ApiException {

    public function __construct(string $message = 'Recurso não encontrado.', array $details = []) {
        parent::__construct(message: $message, statusCode: 404, errorCode: 'NOT_FOUND', details: $details);
    }

}