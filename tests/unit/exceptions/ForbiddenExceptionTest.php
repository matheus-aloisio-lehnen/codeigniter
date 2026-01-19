<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\ForbiddenException;
use PHPUnit\Framework\TestCase;

class ForbiddenExceptionTest extends TestCase {

    public function testConstrutorComValoresPadrao(): void {
        $exception = new ForbiddenException();

        $this->assertSame('Acesso negado.', $exception->getMessage());
        $this->assertSame(403, $exception->statusCode());
        $this->assertSame('FORBIDDEN', $exception->errorCode());
        $this->assertSame([], $exception->details());
    }

    public function testConstrutorComMensagemCustomizadaEDetalhes(): void {
        $details = [
            'action' => 'delete',
            'resource' => 'user',
        ];

        $exception = new ForbiddenException('Você não tem permissão para esta ação.', $details);

        $this->assertSame('Você não tem permissão para esta ação.', $exception->getMessage());
        $this->assertSame(403, $exception->statusCode());
        $this->assertSame('FORBIDDEN', $exception->errorCode());
        $this->assertSame($details, $exception->details());
    }

}