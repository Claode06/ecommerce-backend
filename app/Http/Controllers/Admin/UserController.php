<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['userPoint', 'orders' => function ($q) {
            $q->orderBy('created_at', 'desc')->limit(10);
        }]);

        $totalOrders = $user->orders()->count();

        return view('admin.users.show', compact('user', 'totalOrders'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        Log::info('User deactivated', ['admin_id' => auth('admin')->id(), 'user_id' => $user->id]);

        return redirect()->route('admin.users.index')->with('success', 'Customer berhasil dinonaktifkan.');
    }
}
