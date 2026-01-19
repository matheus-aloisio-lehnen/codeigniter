<?php

namespace unit\helpers;

use App\Helpers\HttpClient;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase {

    #[DataProvider('getAndPostProvider')]
    public function testGetAndPostReturnBody(string $method, string $url, array $headers, array|string|null $body, array $expectedBody): void {
        $client = $this->getMockBuilder(HttpClient::class)
            ->onlyMethods(['send'])
            ->getMock();

        $client->expects($this->once())
            ->method('send')
            ->with(
                strtoupper($method),
                $url,
                $headers,
                $body
            )
            ->willReturn([
                'status' => 200,
                'body'   => $expectedBody,
            ]);

        $result = match ($method) {
            'get'  => $client->get($url, $headers),
            'post' => $client->post($url, $headers, $body),
        };

        $this->assertSame($expectedBody, $result);
    }

    public static function getAndPostProvider(): array {
        return [
            'get sem headers' => [
                'method' => 'get',
                'url' => 'https://example.com',
                'headers' => [],
                'body' => null,
                'expectedBody' => ['ok' => true],
            ],
            'get com headers' => [
                'method' => 'get',
                'url' => 'https://example.com',
                'headers' => ['X-Test: 1'],
                'body' => null,
                'expectedBody' => ['data' => 'value'],
            ],
            'post com json body' => [
                'method' => 'post',
                'url' => 'https://example.com',
                'headers' => [],
                'body' => ['name' => 'Matheus'],
                'expectedBody' => ['id' => 1],
            ],
            'post com string body' => [
                'method' => 'post',
                'url' => 'https://example.com',
                'headers' => ['X-Test: 2'],
                'body' => '{"raw":true}',
                'expectedBody' => ['raw' => true],
            ],
        ];
    }

    #[DataProvider('decodeBodyProvider')]
    public function testDecodeBodyViaSend(string $raw, mixed $expected): void {
        $client = $this->getMockBuilder(HttpClient::class)
            ->onlyMethods(['send'])
            ->getMock();
        $client->method('send')->willReturn(['status' => 200, 'body'   => $expected,]);
        $this->assertSame($expected, $client->get('https://example.com'));
    }

    public static function decodeBodyProvider(): array {
        return [
            'valid json object' => [
                '{"a":1}',
                ['a' => 1],
            ],
            'valid json array' => [
                '[1,2,3]',
                [1, 2, 3],
            ],
            'invalid json' => [
                '<html lang="">error</html>',
                '<html lang="">error</html>',
            ],
            'plain string' => [
                'ok',
                'ok',
            ],
        ];
    }

}