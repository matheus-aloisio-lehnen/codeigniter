<?php

namespace Tests\Unit\UseCase\User;

use App\Exceptions\ConflictException;
use App\Models\UserModel;
use App\UseCases\User\CreateUserUseCase;
use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;

class CreateUserUseCaseTest extends CIUnitTestCase {

    protected function tearDown(): void {
        parent::tearDown();
        Factories::reset();
    }

    private function mockUserModel(array|callable|null $firstReturn): UserModel {
        $model = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first', 'insert', 'getInsertID', '__call'])
            ->getMock();

        // intercepta where() e qualquer outro método mágico
        $model->method('__call')->willReturnSelf();

        if (is_callable($firstReturn)) {
            $model->method('first')->willReturnCallback($firstReturn);
        } else {
            $model->method('first')->willReturn($firstReturn);
        }

        Factories::injectMock('models', UserModel::class, $model);

        return $model;
    }

    public function testLancaExcecaoQuandoEmailJaExiste(): void {
        $this->mockUserModel([
            'id' => 1,
            'email' => 'matheus@example.com',
        ]);

        $useCase = new CreateUserUseCase();

        $this->expectException(ConflictException::class);
        $this->expectExceptionMessage('Email já está cadastrado');

        $useCase->execute('matheus@example.com', 'senhaqualquer');
    }

    public function testCriaUsuarioQuandoEmailNaoExiste(): void {
        $model = $this->mockUserModel(function () {
            static $call = 0;
            $call++;

            // 1ª chamada: verificação de existência
            if ($call === 1) {
                return null;
            }

            // 2ª chamada: busca após insert
            return [
                'id' => 10,
                'email' => 'novo@example.com',
                'password' => 'hash',
            ];
        });

        $model->expects($this->once())
            ->method('insert')
            ->with($this->callback(function (array $data) {
                return $data['email'] === 'novo@example.com'
                    && isset($data['password'])
                    && $data['password'] !== 'senha123'
                    && password_verify('senha123', $data['password']);
            }));

        $model->method('getInsertID')->willReturn(10);

        $useCase = new CreateUserUseCase();

        $result = $useCase->execute('novo@example.com', 'senha123');

        $this->assertSame(10, $result['id']);
        $this->assertSame('novo@example.com', $result['email']);
    }
}