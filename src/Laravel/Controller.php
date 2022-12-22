<?php

namespace LifeSpikes\PhpBeam\Laravel;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller extends \LifeSpikes\LaravelBare\Http\Controller
{
    public function error(string $message, int $status = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }

    /**
     * @param array<mixed,mixed> $data
     * */
    public function success(string $message, array $data, int $status = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json(['message' => $message, 'data' => $data], $status);
    }
}
