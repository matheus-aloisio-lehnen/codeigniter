<?php

namespace App\Helpers;

use App\Exceptions\InternalServerException;

class HttpClient {

    public function get(string $url, array $headers = []): mixed {
        return $this->send(method: 'GET', url: $url, headers: $headers)['body'];
    }

    public function post(string $url, array $headers = [], array|string|null $body = null): mixed {
        return $this->send(method: 'POST', url: $url, headers: $headers, body: $body)['body'];
    }

    public function send(string $method, string $url, array $headers = [], array|string|null $body = null, int $timeout = 10): array {
        $ch = curl_init($url);

        $options = [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $this->buildHeaders($headers, $body),
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_POSTFIELDS     => $this->normalizeBody($body),
        ];

        curl_setopt_array($ch, $options);
        $rawResponse = curl_exec($ch);

        if ($rawResponse === false)
            throw new InternalServerException(curl_error($ch));

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $this->buildResponse(status: $status, raw: $rawResponse);
    }

    private function buildHeaders(array $headers, array|string|null $body): array {
        $default = ['Accept: application/json'];

        if (is_array($body))
            $default[] = 'Content-Type: application/json';

        return array_merge($default, $headers);
    }

    private function normalizeBody(array|string|null $body): ?string {
        return is_array($body)
            ? json_encode($body)
            : $body;
    }

    private function buildResponse(int $status, string $raw): array {
        return [
            'status' => $status,
            'body'   => $this->decodeBody($raw),
        ];
    }

    private function decodeBody(string $raw): mixed {
        $decoded = json_decode($raw, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $raw;
    }

}