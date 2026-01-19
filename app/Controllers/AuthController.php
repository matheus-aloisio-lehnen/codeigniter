<?php

namespace App\Controllers;

use App\UseCases\Auth\LoginUseCase;
use App\Validation\Auth\LoginValidation;
use App\Helpers\RequestHelper;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController {

    protected $format = 'json';

    /**
     * POST /api/sign-in
     */
    public function login(): ResponseInterface {
        $payload = RequestHelper::parseJsonBody($this->request);
        LoginValidation::validate($payload);
        $loginUseCase = new LoginUseCase();
        $response = $loginUseCase->execute($payload['email'], $payload['password']);
        return $this->respond($response);
    }

}
