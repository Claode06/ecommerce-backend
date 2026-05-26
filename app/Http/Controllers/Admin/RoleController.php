<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('admins')->orderBy('name')->paginate(15);

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'status' => 'required|in:0,1',
        ]);

        Role::create($validated);
        Log::info('Role created', ['admin_id' => auth('admin')->id(), 'name' => $validated['name']]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit(Role $role): View
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,'.$role->id,
            'status' => 'required|in:0,1',
        ]);

        $role->update($validated);
        Log::info('Role updated', ['admin_id' => auth('admin')->id(), 'role_id' => $role->id]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        $activeAdminCount = $role->admins()->whereNull('deleted_at')->count();

        if ($activeAdminCount > 0) {
            return back()->with('error', "Role masih digunakan oleh {$activeAdminCount} admin.");
        }

        $role->delete();
        Log::info('Role deleted', ['admin_id' => auth('admin')->id(), 'role_id' => $role->id]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
