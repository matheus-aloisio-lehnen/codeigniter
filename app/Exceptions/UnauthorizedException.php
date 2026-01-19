<?php

namespace App\Exceptions;

class UnauthorizedException extends ApiException {

    public function __construct(string $message = 'Não autorizado') {
        parent::__construct(message: $message, statusCode: 401, errorCode: 'UNAUTHORIZED');
    }

}