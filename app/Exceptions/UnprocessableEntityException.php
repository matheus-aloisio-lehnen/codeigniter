<?php

namespace App\Exceptions;

class UnprocessableEntityException extends ApiException {

    public function __construct(string $message = 'Contrato inválido', array $details = []) {
        parent::__construct(
            message: $message,
            statusCode: 422,
            errorCode: 'UNPROCESSABLE_ENTITY',
            details: $details
        );
    }

}