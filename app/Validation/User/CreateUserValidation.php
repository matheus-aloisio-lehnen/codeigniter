<?php

namespace App\Validation\User;

use App\Exceptions\UnprocessableEntityException;

class CreateUserValidation {

    public static function validate(array $payload): void {
        $validation = service('validation');

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        $isValid = $validation
            ->setRules($rules)
            ->run($payload);

        if (!$isValid)
            throw new UnprocessableEntityException(message: 'Contrato inválido', details: $validation->getErrors());
    }

}