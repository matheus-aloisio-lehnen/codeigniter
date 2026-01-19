<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\BadRequestException;
use PHPUnit\Framework\TestCase;

class BadRequestExceptionTest extends TestCase {

    public function testBadRequestExceptionContrato(): void {
        $details = ['field' => 'invalid'];

        $exception = new BadRequestException(
            message: 'Bad request',
            details: $details
        );

        $this->assertEquals(400, $exception->statusCode());
        $this->assertEquals('Bad request', $exception->getMessage());
        $this->assertEquals($details, $exception->details());
    }

}
