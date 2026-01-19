<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\UnprocessableEntityException;
use PHPUnit\Framework\TestCase;

class UnprocessableEntityExceptionTest extends TestCase {

    public function testExceptionExposeContratoCorreto(): void {
        $details = ['email' => 'invalid'];

        $exception = new UnprocessableEntityException(details: $details);

        $this->assertEquals(422, $exception->statusCode());
        $this->assertEquals('UNPROCESSABLE_ENTITY', $exception->errorCode());
        $this->assertEquals('Contrato inválido', $exception->getMessage());
        $this->assertEquals($details, $exception->details());
    }

}
