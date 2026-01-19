<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class HomeController extends ResourceController
{
    public function index(): ResponseInterface
    {
        $msg = 'Hello world!';
        return $this->respond($msg);
    }
}
