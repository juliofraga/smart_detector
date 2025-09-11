<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function response(Model $data): JsonResponse
    {
        if ($data) {
            return self::responseSuccess($data);
        } else {
            return self::responseError();
        }
    }

    public static function responseOther(bool $info, string $action): JsonResponse
    {
        if ($info) {
            if ($action === 'delete') {
                return self::responseSuccessDelete();
            } else {
                return self::responseSuccessUpdate();
            }
        } else {
            return self::responseError();
        }
    }

    public static function responseSuccess(Model $data): JsonResponse
    {
        return response()->json($data, 201);
    }

    public static function responseSuccessDelete(): JsonResponse
    {
        return response()->json(['message' => 'Registro deletado com sucesso'], 201);
    }

    public static function responseSuccessUpdate(): JsonResponse
    {
        return response()->json(['message' => 'Registro atualizado com sucesso'], 201);
    }

    public static function responseError(): JsonResponse
    {
        return response()->json(['error' => 'Aconteceu um erro, tente novamente.'], 500);
    }

    public static function responseDataNotFound(): JsonResponse
    {
        return response()->json(['error' => 'Dado não encontrado'], 404);
    }

    public static function responseGeneric($data, $statusCode = 201, $type = 'message')
    {
        if (is_string($data)) {
            return response()->json([$type => $data], $statusCode);
        } else {
            return response()->json($data, 201);
        }
    }
}
