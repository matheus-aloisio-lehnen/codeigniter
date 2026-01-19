<?php

namespace Tests\Unit\UseCases\Auth;

use App\UseCases\Auth\LoginUseCase;
use App\Exceptions\UnauthorizedException;
use App\Models\UserModel;
use App\Helpers\JwtHelper;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Factories;
use Config\App;

class LoginUseCaseTest extends CIUnitTestCase {

    protected function setUp(): void {
        parent::setUp();
        $app = config(App::class);
        $app->baseURL = 'https://example.com/';
    }

    protected function tearDown(): void {
        parent::tearDown();
        Factories::reset();
    }

    private function mockUserModel(mixed $firstResult): void {
        $model = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first', '__call'])
            ->getMock();

        // intercepta QUALQUER chamada mágica (where, orderBy, etc)
        $model->method('__call')->willReturnSelf();

        $model->method('first')->willReturn($firstResult);

        Factories::injectMock('models', UserModel::class, $model);
    }

    public function testLancaExcecaoQuandoUsuarioNaoExiste(): void {
        $this->mockUserModel(null);

        $useCase = new LoginUseCase();

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Credenciais inválidas');

        $useCase->execute('naoexiste@example.com', 'senha');
    }

    public function testLancaExcecaoQuandoSenhaInvalida(): void {
        $this->mockUserModel([
            'id' => 1,
            'email' => 'matheus@example.com',
            'password' => password_hash('senha-correta', PASSWORD_DEFAULT),
        ]);

        $useCase = new LoginUseCase();

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Credenciais inválidas');

        $useCase->execute('matheus@example.com', 'senha-errada');
    }

    public function testLoginValidoRetornaToken(): void {
        $this->mockUserModel([
            'id' => 42,
            'email' => 'matheus@example.com',
            'password' => password_hash('senha123', PASSWORD_DEFAULT),
        ]);

        $useCase = new LoginUseCase();

        $result = $useCase->execute('matheus@example.com', 'senha123');

        $this->assertArrayHasKey('accessToken', $result);
        $this->assertSame('Bearer', $result['tokenType']);
        $this->assertSame(3600, $result['expiresIn']);

        $decoded = JwtHelper::decode($result['accessToken']);

        $this->assertSame(42, $decoded->sub);
        $this->assertSame('https://example.com', $decoded->iss);
        $this->assertGreaterThan(time(), $decoded->exp);
    }
}