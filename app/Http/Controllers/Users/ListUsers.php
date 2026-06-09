<?php

namespace App\Http\Controllers\Users;

use App\Data\UserData;
use App\Data\UserListRequestFilters;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class ListUsers extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserListRequestFilters $request): Response
    {
        return Inertia::render('users/List', [
            'users' => static::handle($request),
            'request' => $request,
        ]);
    }

    /**
     * Handle filtering, sorting, and pagination logic.
     */
    public static function handle(UserListRequestFilters $request): mixed
    {
        $query = User::query()
            ->with(['roles'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereAny(['name', 'email'], 'like', '%'.$search.'%')
                        ->orWhereHas('roles', function ($relationQuery) use ($search) {
                            $relationQuery->where('name', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when($request->role, function ($query, $role) {
                $query->whereHas('roles', function ($relationQuery) use ($role) {
                    $relationQuery->where('name', $role->value ?? $role);
                });
            });

        // Dynamic Sorting Engine
        $sort = $request->sort ?? '-created_at'; // defaulting to newest first
        $sortField = ltrim($sort, '-');
        $sortDirection = str_starts_with($sort, '-') ? 'desc' : 'asc';

        if ($sortField === 'role') {
            // Sort by primary role via subquery or relation count fallback
            $query->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->orderBy('roles.name', $sortDirection)
                ->select('users.*');
        } else {
            $userColumns = Cache::remember('schema_columns_users', 300, static function () {
                return Schema::getColumnListing((new User)->getTable());
            });

            if (in_array($sortField, $userColumns, true)) {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy('created_at', 'desc');
            }
        }

        $users = $query->paginate($request->per_page)->withQueryString();

        return UserData::collect($users);
    }
}
