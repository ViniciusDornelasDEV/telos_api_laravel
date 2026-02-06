<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Supplier\Models\Supplier;
use Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class SupplierUserController extends Controller
{
    public function index(Supplier $supplier): JsonResponse
    {
        Gate::authorize('update', User::class);
        return response()->json($supplier->users);
    }

    public function attach(Supplier $supplier, User $user): JsonResponse
    {
        Gate::authorize('update', User::class);
        $supplier->users()->syncWithoutDetaching([$user->id]);

        return response()->json([
            'message' => 'Usuário vinculado ao fornecedor com sucesso'
        ]);
    }

    public function detach(Supplier $supplier, User $user): JsonResponse
    {
        Gate::authorize('update', User::class);
        $supplier->users()->detach($user->id);

        return response()->json([
            'message' => 'Usuário desvinculado do fornecedor com sucesso'
        ]);
    }
}
