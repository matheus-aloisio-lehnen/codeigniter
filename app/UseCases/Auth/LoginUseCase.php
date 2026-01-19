<?php

namespace App\UseCases\Auth;

use App\Exceptions\UnauthorizedException;
use App\Helpers\JwtHelper;
use App\Models\UserModel;
use Config\App;

class LoginUseCase {

    public function execute(string $email, string $password): array {
        $userModel = model(UserModel::class);
        $user = $userModel->where('email', $email)->first();

        if ($user === null)
            throw new UnauthorizedException('Credenciais inválidas');

        if (!password_verify($password, $user['password']))
            throw new UnauthorizedException('Credenciais inválidas');

        return ['accessToken' => $this->generateToken((int) $user['id']),];
    }

    private function generateToken(int $userId): string {
        $now = time();
        $app = config(App::class);
        return JwtHelper::encode([
            'sub' => $userId,
            'iat' => $now,
            'exp' => $now + $this->getTtl(),
            'iss' => rtrim($app->baseURL, '/'),
        ]);
    }

    private function getTtl(): int {
        return (int) env('JWT_TTL', 3600);
    }

}
