<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\User\Http\Requests\InsertUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
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

    public function insert(InsertUserRequest $request)
    {
        $user = $this->service->insert($request->validated());

        return response()->json($user, 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $updatedUser = $this->service->update($user, $request->validated());

        return response()->json($updatedUser);
    }
}
