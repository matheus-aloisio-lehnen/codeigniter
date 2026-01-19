<?php

namespace App\Libraries\AuthContext;

use App\Exceptions\UnauthorizedException;

final class AuthContext {

    private static ?object $user = null;

    private function __construct() {}

    private function __clone() {}

    public static function set(object $user): void {
        self::$user = $user;
    }

    public static function user(): object {
        if (self::$user === null)
            throw new UnauthorizedException('Usuário não autenticado');

        return self::$user;
    }

    public static function hasUser(): bool {
        return self::$user !== null;
    }

    public static function clear(): void {
        self::$user = null;
    }

}