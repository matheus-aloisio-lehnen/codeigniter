<?php

namespace Tests\Unit\UseCase\User;

use App\Exceptions\NotFoundException;
use App\Models\UserModel;
use App\UseCases\User\GetUserUseCase;
use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;

class GetUserUseCaseTest extends CIUnitTestCase {

    protected function tearDown(): void {
        parent::tearDown();
        Factories::reset();
    }

    public function testRetornaUsuarioQuandoExiste(): void {
        $user = [
            'id' => 10,
            'email' => 'matheus@example.com',
        ];

        $model = $this->createMock(UserModel::class);

        $model->method('find')
            ->with(10)
            ->willReturn($user);

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new GetUserUseCase();

        $result = $useCase->execute(10);

        $this->assertSame($user, $result);
    }

    public function testLancaExcecaoQuandoUsuarioNaoExiste(): void {
        $model = $this->createMock(UserModel::class);

        $model->method('find')
            ->with(99)
            ->willReturn(null);

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new GetUserUseCase();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Usuário não encontrado');

        $useCase->execute(99);
    }

}
