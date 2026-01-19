<?php

namespace App\Exceptions;

class InternalServerException extends ApiException {

    public function __construct(string $message = 'Erro interno inesperado.', array $details = []) {
        parent::__construct(message: $message, statusCode: 500, errorCode: 'INTERNAL_SERVER_ERROR', details: $details);
    }

}