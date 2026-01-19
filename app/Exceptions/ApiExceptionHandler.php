<?php

namespace App\Exceptions;

use CodeIgniter\Debug\ExceptionHandlerInterface;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class ApiExceptionHandler implements ExceptionHandlerInterface {
    public function handle(Throwable $exception, CLIRequest|IncomingRequest|RequestInterface $request, ResponseInterface $response, int $statusCode, int $exitCode): void {
        if ($exception instanceof ApiException) {
            $response
                ->setStatusCode($exception->statusCode())
                ->setJSON([
                    'error' => [
                        'code'    => $exception->errorCode(),
                        'message' => $exception->getMessage(),
                        'details' => $exception->details(),
                    ],
                ])
                ->send();

            return;
        }

        // fallback seguro
        $response
            ->setStatusCode(500)
            ->setJSON([
                'error' => [
                    'code'    => 'INTERNAL_SERVER_ERROR',
                    'message' => 'Erro interno inesperado',
                    'details' => [],
                ],
            ])
            ->send();
    }
}