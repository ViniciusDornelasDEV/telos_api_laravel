<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Modules\User\Models\User;

class InsertUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('insert', User::class);
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string',
            'status'   => 'required|boolean',
            'type'     => 'required|in:admin,seller',
        ];
    }
}
