<?php

namespace App\Param;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Param
{
    public function getArray(Request $request): array
    {
        return [
            'begin' => htmlspecialchars($request->query->get('begin')),
            'end' => htmlspecialchars($request->query->get('end')),
            'search' => htmlspecialchars($request->query->get('search')),
        ];
    }

    public function hasDateScope(Request $request): bool
    {
        if ('' == $request->query->get('begin') || '' == $request->query->get('end')) {
            return false;
        } else {
            return true;
        }
    }

    public function hasFilter(Request $request): bool
    {
        if ('' == $request->query->get('search')) {
            return false;
        } else {
            return true;
        }
    }

    public function addHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => 'localhost:3000',
            'Access-Control-Allow-Credentials' => 'true',
        ];
    }

    public function successResponse(array $body): Response
    {
        return new Response(json_encode($body), 200, $this->addHeaders());
    }
}
