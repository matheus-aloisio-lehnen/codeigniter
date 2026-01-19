<?php

namespace Tests\Unit\UseCase\User;

use App\Exceptions\NotFoundException;
use App\Models\UserModel;
use App\UseCases\User\DeleteUserUseCase;
use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;

class DeleteUserUseCaseTest extends CIUnitTestCase {

    protected function tearDown(): void {
        parent::tearDown();
        Factories::reset();
    }

    public function testLancaExcecaoQuandoUsuarioNaoExiste(): void {
        $model = $this->createMock(UserModel::class);

        $model->method('find')
            ->with(1)
            ->willReturn(null);

        $model->expects($this->never())
            ->method('delete');

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new DeleteUserUseCase();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Usuário não encontrado');

        $useCase->execute(1);
    }

    public function testDeletaUsuarioQuandoExiste(): void {
        $model = $this->createMock(UserModel::class);

        $model->method('find')
            ->with(10)
            ->willReturn([
                'id' => 10,
                'email' => 'matheus@example.com',
            ]);

        $model->expects($this->once())
            ->method('delete')
            ->with(10)
            ->willReturn(true);

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new DeleteUserUseCase();

        $result = $useCase->execute(10);

        $this->assertTrue($result);
    }

}
