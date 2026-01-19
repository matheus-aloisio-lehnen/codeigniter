<?php

namespace Tests\Unit\Validation;

use App\Exceptions\UnprocessableEntityException;
use App\Validation\Auth\LoginValidation;
use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class LoginValidationTest extends CIUnitTestCase {

    protected function setUp(): void {
        parent::setUp();
        Factories::reset();
    }

    public function testPayloadValidoNaoLancaExcecao(): void {
        service('validation')->reset();

        $payload = [
            'email' => 'email@teste.com',
            'password' => '123456',
        ];

        $this->expectNotToPerformAssertions();

        LoginValidation::validate($payload);
    }

    #[DataProvider('invalidPayloadProvider')]
    public function testPayloadInvalidoLancaExcecao(array $payload): void {
        // 🔥 limpa estado global do validation
        service('validation')->reset();

        $this->expectException(UnprocessableEntityException::class);
        $this->expectExceptionMessage('Contrato inválido');

        LoginValidation::validate($payload);
    }

    public static function invalidPayloadProvider(): array {
        return [
            'email invalido' => [[
                'email' => 'email-invalido',
                'password' => '123456',
            ]],
            'password vazio' => [[
                'email' => 'user@example.com',
                'password' => '',
            ]],
            'ambos vazios' => [[
                'email' => '',
                'password' => '',
            ]],
            'payload vazio' => [[]],
        ];
    }
}
