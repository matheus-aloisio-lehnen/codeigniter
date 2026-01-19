<?php

namespace Tests\Unit\UseCases\User;

use App\UseCases\User\UpdateUserUseCase;
use App\Exceptions\NotFoundException;
use App\Exceptions\ConflictException;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Factories;

class UpdateUserUseCaseTest extends CIUnitTestCase {

    protected function tearDown(): void {
        parent::tearDown();
        Factories::reset();
    }

    public function testLancaExcecaoQuandoUsuarioNaoExiste(): void {
        $model = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first', '__call'])
            ->getMock();

        $model->method('__call')->with('where')->willReturnSelf();
        $model->method('first')->willReturn(null);

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new UpdateUserUseCase();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('User not found');

        $useCase->execute(1, []);
    }

    public function testRetornaUsuarioOriginalQuandoPayloadVazio(): void {
        $user = [
            'id' => 1,
            'email' => 'matheus@example.com',
        ];

        $model = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first', '__call'])
            ->getMock();

        $model->method('__call')->with('where')->willReturnSelf();
        $model->method('first')->willReturn($user);

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new UpdateUserUseCase();

        $result = $useCase->execute(1, []);

        $this->assertSame($user, $result);
    }

    public function testAtualizaEmailQuandoNovoEmailValido(): void {
        $originalUser = [
            'id' => 1,
            'email' => 'antigo@example.com',
        ];

        $updatedUser = [
            'id' => 1,
            'email' => 'novo@example.com',
        ];

        $model = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first', '__call', 'update'])
            ->getMock();

        $model->method('__call')->with('where')->willReturnSelf();
        $model->method('first')->willReturnOnConsecutiveCalls(
            $originalUser, // busca inicial
            null,          // valida email duplicado
            $updatedUser   // retorno final
        );

        $model->expects($this->once())
            ->method('update')
            ->with(1, ['email' => 'novo@example.com']);

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new UpdateUserUseCase();

        $result = $useCase->execute(1, ['email' => 'novo@example.com']);

        $this->assertSame('novo@example.com', $result['email']);
    }

    public function testLancaExcecaoQuandoEmailJaExiste(): void {
        $user = [
            'id' => 1,
            'email' => 'antigo@example.com',
        ];

        $model = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first', '__call'])
            ->getMock();

        $model->method('__call')->with('where')->willReturnSelf();
        $model->method('first')->willReturnOnConsecutiveCalls(
            $user,
            ['id' => 2, 'email' => 'novo@example.com']
        );

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new UpdateUserUseCase();

        $this->expectException(ConflictException::class);
        $this->expectExceptionMessage('Email já está cadastrado');

        $useCase->execute(1, ['email' => 'novo@example.com']);
    }

    public function testLancaExcecaoQuandoSenhaVazia(): void {
        $user = [
            'id' => 1,
            'email' => 'matheus@example.com',
        ];

        $model = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first', '__call'])
            ->getMock();

        $model->method('__call')->with('where')->willReturnSelf();
        $model->method('first')->willReturn($user);

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new UpdateUserUseCase();

        $this->expectException(ConflictException::class);
        $this->expectExceptionMessage('Senha não pode ser vazia');

        $useCase->execute(1, ['password' => '   ']);
    }

    public function testAtualizaSenhaComHash(): void {
        $user = [
            'id' => 1,
            'email' => 'matheus@example.com',
        ];

        $updatedUser = [
            'id' => 1,
            'email' => 'matheus@example.com',
            'password' => 'hash',
        ];

        $model = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first', '__call', 'update'])
            ->getMock();

        $model->method('__call')->with('where')->willReturnSelf();
        $model->method('first')->willReturnOnConsecutiveCalls(
            $user,
            $updatedUser
        );

        $model->expects($this->once())
            ->method('update')
            ->with(
                1,
                $this->callback(function (array $data) {
                    return isset($data['password'])
                        && password_verify('senha123', $data['password']);
                })
            );

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new UpdateUserUseCase();

        $result = $useCase->execute(1, ['password' => 'senha123']);

        $this->assertSame(1, $result['id']);
    }
}