<?php

namespace App\Helpers;

use App\Exceptions\BadRequestException;
use CodeIgniter\HTTP\IncomingRequest;
use stdClass;

class RequestHelper {

    public static function parseJsonBody(IncomingRequest $request): array {
        $raw = $request->getBody();

        if ($raw === null || trim($raw) === '')
            return [];

        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE)
            throw new BadRequestException('JSON inválido');

        if (!is_array($decoded))
            throw new BadRequestException('JSON inválido');

        return $decoded;
    }

}