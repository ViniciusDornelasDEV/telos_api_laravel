<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\User\Services\UserService;
use Modules\User\Models\User;
use Modules\User\Http\Resources\UserResource;

class UserController extends Controller
{
    public function __construct(
        protected UserService $service
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('index', User::class);
        $users = $this->service->list();

        return response()->json(
            UserResource::collection($users)->resolve(),
            201
        );
    }

    public function insert(Request $request)
    {
        Gate::authorize('insert', User::class);
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string',
            'status'  => 'required|boolean',
            'type'     => 'required|in:admin,seller',
        ]);

        $user = $this->service->insert($data);

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('update', User::class);
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'status'  => 'required|boolean',
            'type'     => 'required|in:admin,seller',
        ]);

        $updatedUser = $this->service->update($user, $data);

        return response()->json($updatedUser);
    }
}
