<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\NotFoundException;
use PHPUnit\Framework\TestCase;

class NotFoundExceptionTest extends TestCase {

    public function testConstrutorComValoresPadrao(): void {
        $exception = new NotFoundException();

        $this->assertSame('Recurso não encontrado.', $exception->getMessage());
        $this->assertSame(404, $exception->statusCode());
        $this->assertSame('NOT_FOUND', $exception->errorCode());
        $this->assertSame([], $exception->details());
    }

    public function testConstrutorComMensagemCustomizadaEDetalhes(): void {
        $details = [
            'resource' => 'user',
            'id'       => 123,
        ];

        $exception = new NotFoundException('Usuário não encontrado.', $details);

        $this->assertSame('Usuário não encontrado.', $exception->getMessage());
        $this->assertSame(404, $exception->statusCode());
        $this->assertSame('NOT_FOUND', $exception->errorCode());
        $this->assertSame($details, $exception->details());
    }

}