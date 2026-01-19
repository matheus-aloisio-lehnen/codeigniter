<?php

namespace App\Validation\User;

use App\Exceptions\UnprocessableEntityException;

class UpdateUserValidation {

    public static function validate(array $payload): void {
        $validation = service('validation');

        $rules = [
            'email'    => 'permit_empty|valid_email',
            'password' => 'permit_empty|min_length[6]',
        ];

        $isValid = $validation
            ->setRules($rules)
            ->run($payload);

        if (!$isValid)
            throw new UnprocessableEntityException(message: 'Contrato inválido', details: $validation->getErrors());
    }

}