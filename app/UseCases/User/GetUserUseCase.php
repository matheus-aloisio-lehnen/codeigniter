<?php

namespace App\UseCases\User;

use App\Exceptions\NotFoundException;
use App\Models\UserModel;

class GetUserUseCase {


    public function execute(int $id): array {
        $userModel = model(UserModel::class);
        $user = $userModel
            ->select('id, email, created_at, updated_at')
            ->find($id);
        if ($user === null)
            throw new NotFoundException('Usuário não encontrado');
        return $user;
    }

}