<?php

namespace App\UseCases\User;

use App\Models\UserModel;

class GetUsersUseCase {

    public function execute(): array {
        $userModel = model(UserModel::class);
        return $userModel
            ->select('id, email, created_at, updated_at')
            ->findAll();
    }

}