<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        $roleModel = \App\Models\Role::find($admin->role_id);

        if (! $roleModel || $roleModel->name !== $role) {
            abort(403);
        }

        return $next($request);
    }
}
