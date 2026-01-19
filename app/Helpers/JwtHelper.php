<?php

namespace App\Helpers;

use App\Exceptions\UnauthorizedException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;
use Throwable;

class JwtHelper {

    public static function encode(array $payload): string {
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    public static function decode(string $token): stdClass {
        try {
            return JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (Throwable) {
            throw new UnauthorizedException('Token inválido ou expirado');
        }
    }

}