<?php

namespace Tests\Unit\Helpers;

use App\Helpers\RequestHelper;
use App\Exceptions\BadRequestException;
use CodeIgniter\HTTP\IncomingRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RequestHelperTest extends TestCase {

    #[DataProvider('emptyBodyProvider')]
    public function testParseJsonBodyRetornaArrayVazioParaBodyVazioOuNulo(?string $body): void {
        $request = $this->mockRequestWithBody($body);

        $result = RequestHelper::parseJsonBody($request);

        $this->assertSame([], $result);
    }

    #[DataProvider('validJsonProvider')]
    public function testParseJsonBodyRetornaArrayParaJsonValido(string $body, array $expected): void {
        $request = $this->mockRequestWithBody($body);

        $result = RequestHelper::parseJsonBody($request);

        $this->assertSame($expected, $result);
    }

    #[DataProvider('invalidJsonProvider')]
    public function testParseJsonBodyLancaExcecaoParaJsonInvalido(string $body): void {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('JSON inválido');

        $request = $this->mockRequestWithBody($body);

        RequestHelper::parseJsonBody($request);
    }

    #[DataProvider('nonArrayJsonProvider')]
    public function testParseJsonBodyLancaExcecaoParaJsonValidoNaoArray(string $body): void {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('JSON inválido');

        $request = $this->mockRequestWithBody($body);

        RequestHelper::parseJsonBody($request);
    }

    /* ============================
       Data Providers
       ============================ */

    public static function emptyBodyProvider(): array {
        return [
            'body nulo' => [null],
            'string vazia' => [''],
            'apenas espaços' => ['   '],
            'quebra de linha' => ["\n"],
        ];
    }

    public static function validJsonProvider(): array {
        return [
            'json simples' => [
                '{"name":"Matheus","age":30}',
                ['name' => 'Matheus', 'age' => 30],
            ],
            'json array vazio' => [
                '[]',
                [],
            ],
            'json aninhado' => [
                '{"user":{"id":1,"roles":["admin"]}}',
                ['user' => ['id' => 1, 'roles' => ['admin']]],
            ],
        ];
    }

    public static function invalidJsonProvider(): array {
        return [
            'json malformado' => ['{"name":'],
            'aspas simples' => ["{'name':'Matheus'}"],
            'texto puro' => ['qualquer coisa'],
        ];
    }

    public static function nonArrayJsonProvider(): array {
        return [
            'json string' => ['"string"'],
            'json numero' => ['123'],
            'json booleano' => ['true'],
            'json null' => ['null'],
        ];
    }

    /* ============================
       Helper interno de mock
       ============================ */

    private function mockRequestWithBody(?string $body): IncomingRequest {
        $request = $this->createMock(IncomingRequest::class);

        $request->method('getBody')
            ->willReturn($body);

        return $request;
    }

}
