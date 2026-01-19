<?php

namespace App\Controllers;

use App\Helpers\RequestHelper;
use App\UseCases\User\CreateUserUseCase;
use App\UseCases\User\DeleteUserUseCase;
use App\UseCases\User\GetUsersUseCase;
use App\UseCases\User\GetUserUseCase;
use App\UseCases\User\UpdateUserUseCase;
use App\Validation\User\CreateUserValidation;
use App\Validation\User\UpdateUserValidation;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController {

    protected $format = 'json';

    /**
     * GET /api/users
     */
    public function index() {
        $useCase = new GetUsersUseCase();
        $users = $useCase->execute();
        return $this->respond($users);
    }

    /**
     * GET /api/users/{id}
     */
    public function show($id = null) {
        $useCase = new GetUserUseCase();
        $user = $useCase->execute((int) $id);
        return $this->respond($user);
    }

    /**
     * POST /api/users
     */
    public function create() {
        $payload = RequestHelper::parseJsonBody($this->request);
        CreateUserValidation::validate($payload);

        $useCase = new CreateUserUseCase();
        $response = $useCase->execute(
            $payload['email'],
            $payload['password']
        );

        return $this->respondCreated($response);
    }

    /**
     * PUT /api/users/{id}
     */
    public function update($id = null) {
        $payload = RequestHelper::parseJsonBody($this->request);
        UpdateUserValidation::validate($payload);

        $useCase = new UpdateUserUseCase();
        $response = $useCase->execute($id, $payload);

        return $this->respond($response);
    }

    /**
     * DELETE /api/users/{id}
     */
    public function delete($id = null) {
        $useCase = new DeleteUserUseCase();
        $response = $useCase->execute($id);

        return $this->respondDeleted($response);
    }

}