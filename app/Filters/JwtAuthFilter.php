<?php

namespace App\Filters;

use App\Exceptions\UnauthorizedException;
use App\Helpers\JwtHelper;
use App\Libraries\AuthContext\AuthContext;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class JwtAuthFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null): ?ResponseInterface
    {
        $token = $this->extractBearerToken($request);

        if ($token === null) {
            throw new UnauthorizedException('Autorização inválida');
        }

        $decodedToken = JwtHelper::decode($token);

        AuthContext::set($decodedToken);

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}


    // ---------------------------------------------------------------------
    // Private helpers
    // ---------------------------------------------------------------------

    private function extractBearerToken(RequestInterface $request): ?string {
        $authorizationHeader = $request->getHeaderLine('Authorization');

        if ($authorizationHeader === '')
            return null;

        if (!str_starts_with($authorizationHeader, 'Bearer '))
            return null;

        return substr($authorizationHeader, 7);
    }

}