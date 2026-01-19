<?php

namespace App\UseCases\User;

use App\Exceptions\NotFoundException;
use App\Models\UserModel;

class DeleteUserUseCase {

    public function execute(int $id): bool {
        $userModel = model(UserModel::class);
        $user = $userModel->find($id);

        if (!$user)
            throw new NotFoundException('Usuário não encontrado');

        return $userModel->delete($id);
    }

}