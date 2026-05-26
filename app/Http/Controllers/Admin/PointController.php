<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PointController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with('userPoint')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->whereHas('userPoint')
            ->orderBy(
                \App\Models\UserPoint::select('balance')
                    ->whereColumn('user_id', 'users.id')
                    ->orderBy('balance', 'desc')
                    ->limit(1),
                'desc'
            )
            ->paginate(15);

        return view('admin.points.index', compact('users'));
    }

    public function show(User $user): View
    {
        $transactions = $user->pointTransactions()
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $balance = $user->userPoint?->balance ?? 0;

        return view('admin.points.show', compact('user', 'transactions', 'balance'));
    }
}
