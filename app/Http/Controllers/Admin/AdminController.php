<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $admins = Admin::with('role')->orderBy('name')->paginate(15);

        return view('admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        $roles = Role::where('status', 1)->orderBy('name')->get();

        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:0,1',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        Admin::create($validated);

        Log::info('Admin created', ['admin_id' => auth('admin')->id(), 'name' => $validated['name']]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dibuat.');
    }

    public function edit(Admin $admin): View
    {
        $roles = Role::where('status', 1)->orderBy('name')->get();

        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admins,email,'.$admin->id,
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:0,1',
        ]);

        $admin->update($validated);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function resetPassword(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Password berhasil direset.');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $admin->delete();
        Log::info('Admin deleted', ['admin_id' => auth('admin')->id(), 'deleted_id' => $admin->id]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}
