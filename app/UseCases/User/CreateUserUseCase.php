<?php

namespace App\UseCases\User;

use App\Exceptions\ConflictException;
use App\Models\UserModel;

class CreateUserUseCase {

    public function execute(string $email, string $password): array {
        $userModel = model(UserModel::class);
        $existingUser = $userModel
            ->where('email', $email)
            ->first();

        if ($existingUser !== null)
            throw new ConflictException('Email já está cadastrado');

        $userModel->insert([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return $userModel->where("id", $userModel->getInsertID())->first();
    }
}