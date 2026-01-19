<?php

namespace Tests\Unit\Helpers;

use App\Helpers\JwtHelper;
use App\Exceptions\UnauthorizedException;
use PHPUnit\Framework\TestCase;

class JwtHelperTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();
    }

    public function testEncodeGeraTokenValido(): void {
        $payload = [
            'sub' => 1,
            'email' => 'matheus@example.com',
        ];
        $token = JwtHelper::encode($payload);
        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    public function testDecodeRetornaPayloadParaTokenValido(): void {
        $payload = [
            'sub' => 1,
            'email' => 'matheus@example.com',
        ];
        $token = JwtHelper::encode($payload);
        $decoded = JwtHelper::decode($token);
        $this->assertSame(1, $decoded->sub);
        $this->assertSame('matheus@example.com', $decoded->email);
    }

    public function testDecodeLancaExcecaoParaTokenInvalido(): void {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Token inválido ou expirado');
        JwtHelper::decode('token.invalido.aqui');
    }

}
