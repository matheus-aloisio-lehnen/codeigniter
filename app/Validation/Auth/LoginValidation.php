<?php

namespace App\Validation\Auth;

use App\Exceptions\UnprocessableEntityException;

class LoginValidation {

    public static function validate(array $payload): void {
        $validation = service('validation');

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        $isValid = $validation
            ->setRules($rules)
            ->run($payload);

        if (!$isValid)
            throw new UnprocessableEntityException(message: 'Contrato inválido', details: $validation->getErrors());
    }

}