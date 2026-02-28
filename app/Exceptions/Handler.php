<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Handler
{
    public static function renderValidationException(ValidationException $e, Request $request): ?JsonResponse
    {
        if (! $request->expectsJson()) {
            return null;
        }

        $errors = $e->errors();
        $fields = is_array($errors) ? $errors : $errors->toArray();

        return new JsonResponse([
            'success' => false,
            'error' => 'validation_error',
            'message' => 'Validation failed',
            'fields' => $fields,
        ], $e->status);
    }
}
