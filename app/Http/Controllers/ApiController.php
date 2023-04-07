<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ApiController extends Controller
{

    private function successResponse($data, $code, $status, $message)
    {
        return new JsonResponse([
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }
    protected function errorResponse($code, $status, $message)
    {
        return new JsonResponse([
            'status' => $status,
            'code' => $code,
            'message' => [$message],
        ]);
    }

    protected function showAll(Collection $collection, $code, $status, $message)
    {
        return $this->successResponse(
            $collection,
            $code,
            $status,
            $message
        );
    }

    protected function showOne(Model $model, $code, $status, $message)
    {
        return $this->successResponse(
            $model,
            $code,
            $status,
            $message
        );
    }
}