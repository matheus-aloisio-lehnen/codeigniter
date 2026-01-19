<?php

namespace App\UseCases\User;

use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use App\Models\UserModel;

class UpdateUserUseCase {

    public function execute(int $id, array $payload): array {
        $userModel = model(UserModel::class);
        $user = $userModel->where('id', $id)->first();

        if ($user === null)
            throw new NotFoundException('User not found');

        $updateDto = [];

        if (array_key_exists('email', $payload))
            $updateDto['email'] = $this->validateAndGetNewEmail($userModel, $id, $user, $payload['email']);

        if (array_key_exists('password', $payload))
            $updateDto['password'] = $this->validateAndHashPassword($payload['password']);

        if ($updateDto === [])
            return $user;

        $userModel->update($id, $updateDto);
        return $userModel->where('id', $id)->first();
    }

    private function validateAndGetNewEmail($userModel, int $id, array $user, string $email): ?string {
        if ($email === $user['email'])
            return null;

        $emailAlreadyExists = $userModel
            ->where('email', $email)
            ->where('id !=', $id)
            ->first();

        if ($emailAlreadyExists !== null)
            throw new ConflictException('Email já está cadastrado');

        return $email;
    }

    private function validateAndHashPassword(?string $password): string {
        if (trim((string) $password) === '')
            throw new ConflictException('Senha não pode ser vazia');

        return password_hash($password, PASSWORD_DEFAULT);
    }

}