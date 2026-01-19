<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\InternalServerException;
use PHPUnit\Framework\TestCase;

class InternalServerExceptionTest extends TestCase {

    public function testConstrutorComValoresPadrao(): void {
        $exception = new InternalServerException();

        $this->assertSame('Erro interno inesperado.', $exception->getMessage());
        $this->assertSame(500, $exception->statusCode());
        $this->assertSame('INTERNAL_SERVER_ERROR', $exception->errorCode());
        $this->assertSame([], $exception->details());
    }

    public function testConstrutorComMensagemCustomizadaEDetalhes(): void {
        $details = [
            'service' => 'database',
            'reason'  => 'timeout',
        ];

        $exception = new InternalServerException('Falha ao processar a requisição.', $details);

        $this->assertSame('Falha ao processar a requisição.', $exception->getMessage());
        $this->assertSame(500, $exception->statusCode());
        $this->assertSame('INTERNAL_SERVER_ERROR', $exception->errorCode());
        $this->assertSame($details, $exception->details());
    }

}