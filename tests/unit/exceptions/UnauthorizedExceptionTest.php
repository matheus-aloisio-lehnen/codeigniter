<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\UnauthorizedException;
use PHPUnit\Framework\TestCase;

class UnauthorizedExceptionTest extends TestCase {

    public function testUnauthorizedExceptionContrato(): void {
        $exception = new UnauthorizedException();

        $this->assertEquals(401, $exception->statusCode());
        $this->assertEquals('UNAUTHORIZED', $exception->errorCode());
        $this->assertEquals('Não autorizado', $exception->getMessage());
        $this->assertSame([], $exception->details());
    }

}
