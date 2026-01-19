<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\ConflictException;
use PHPUnit\Framework\TestCase;

class ConflictExceptionTest extends TestCase {

    public function testConstrutorComValoresPadrao(): void {
        $exception = new ConflictException();

        $this->assertSame('Conflito de estado.', $exception->getMessage());
        $this->assertSame(409, $exception->statusCode());
        $this->assertSame('CONFLICT', $exception->errorCode());
        $this->assertSame([], $exception->details());
    }

    public function testConstrutorComMensagemCustomizadaEDetalhes(): void {
        $details = [
            'resource' => 'user',
            'field' => 'email',
            'reason' => 'já existe',
        ];

        $exception = new ConflictException('E-mail já cadastrado.', $details);

        $this->assertSame('E-mail já cadastrado.', $exception->getMessage());
        $this->assertSame(409, $exception->statusCode());
        $this->assertSame('CONFLICT', $exception->errorCode());
        $this->assertSame($details, $exception->details());
    }

}
