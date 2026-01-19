<?php

namespace Tests\Unit\Validation\User;

use App\Validation\User\CreateUserValidation;
use App\Exceptions\UnprocessableEntityException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CreateUserValidationTest extends TestCase {

    #[DataProvider('validPayloadProvider')]
    public function testValidateNaoDisparaExcecaoParaPayloadValido(array $payload): void {
        $this->expectNotToPerformAssertions();

        CreateUserValidation::validate($payload);
    }

    #[DataProvider('invalidPayloadProvider')]
    public function testValidateDisparaExcecaoParaPayloadInvalido(array $payload): void {
        try {
            CreateUserValidation::validate($payload);
            $this->fail('Era esperada uma exceção UnprocessableEntityException, mas nenhuma foi lançada.');
        } catch (UnprocessableEntityException $exception) {
            $this->assertSame('Contrato inválido', $exception->getMessage());
            $this->assertSame(422, $exception->statusCode());
            $this->assertSame('UNPROCESSABLE_ENTITY', $exception->errorCode());
            $this->assertNotEmpty(
                $exception->details(),
                'Os detalhes do erro de validação não deveriam estar vazios.'
            );
        }
    }

    public static function validPayloadProvider(): array {
        return [
            'payload válido' => [
                [
                    'email' => 'matheus@example.com',
                    'password' => '123456',
                ],
            ],
        ];
    }

    public static function invalidPayloadProvider(): array {
        return [
            'email ausente' => [
                [
                    'password' => '123456',
                ],
            ],
            'email inválido' => [
                [
                    'email' => 'email-invalido',
                    'password' => '123456',
                ],
            ],
            'senha ausente' => [
                [
                    'email' => 'matheus@example.com',
                ],
            ],
            'senha muito curta' => [
                [
                    'email' => 'matheus@example.com',
                    'password' => '123',
                ],
            ],
            'payload vazio' => [
                [],
            ],
        ];
    }
}