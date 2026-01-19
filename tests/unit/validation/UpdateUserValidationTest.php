<?php

namespace Tests\Unit\Validation\User;

use App\Validation\User\UpdateUserValidation;
use App\Exceptions\UnprocessableEntityException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UpdateUserValidationTest extends TestCase {

    #[DataProvider('validPayloadProvider')]
    public function testPayloadValidoNaoLancaExcecao(array $payload): void {
        // 🔥 LIMPA estado global do Validation
        service('validation')->reset();

        $this->expectNotToPerformAssertions();

        UpdateUserValidation::validate($payload);
    }

    #[DataProvider('invalidPayloadProvider')]
    public function testPayloadInvalidoLancaExcecao(array $payload): void {
        // 🔥 LIMPA estado global do Validation
        service('validation')->reset();

        $this->expectException(UnprocessableEntityException::class);
        $this->expectExceptionMessage('Contrato inválido');

        UpdateUserValidation::validate($payload);
    }

    public static function validPayloadProvider(): array {
        return [
            'email válido' => [
                [
                    'email' => 'matheus@example.com',
                ],
            ],
            'senha válida' => [
                [
                    'password' => '123456',
                ],
            ],
            'email e senha válidos' => [
                [
                    'email'    => 'matheus@example.com',
                    'password' => '123456',
                ],
            ],
            'campos extras são ignorados' => [
                [
                    'email' => 'matheus@example.com',
                    'role'  => 'admin',
                ],
            ],
        ];
    }

    public static function invalidPayloadProvider(): array {
        return [
            'email inválido' => [
                [
                    'email' => 'email-invalido',
                ],
            ],
            'senha muito curta' => [
                [
                    'password' => '123',
                ],
            ],
            'email inválido com outros campos' => [
                [
                    'email'    => 'email-invalido',
                    'password' => '123456',
                ],
            ],
            'senha inválida com outros campos' => [
                [
                    'email'    => 'matheus@example.com',
                    'password' => '123',
                ],
            ],
        ];
    }
}