<?php

namespace Tests\Unit\UseCase\User;

use App\Models\UserModel;
use App\UseCases\User\GetUsersUseCase;
use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;

class GetUsersUseCaseTest extends CIUnitTestCase {

    protected function tearDown(): void {
        parent::tearDown();
        Factories::reset();
    }

    public function testRetornaListaDeUsuarios(): void {
        $users = [
            ['id' => 1, 'email' => 'a@example.com'],
            ['id' => 2, 'email' => 'b@example.com'],
        ];

        $model = $this->createMock(UserModel::class);

        $model->expects($this->once())
            ->method('findAll')
            ->willReturn($users);

        Factories::injectMock('models', UserModel::class, $model);

        $useCase = new GetUsersUseCase();

        $result = $useCase->execute();

        $this->assertSame($users, $result);
    }

}
