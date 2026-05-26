<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthorizationType;
use App\Models\Module;
use App\Models\ModuleGroup;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthorizationController extends Controller
{
    public function edit(Role $role): View
    {
        $moduleGroups = ModuleGroup::with(['modules' => function ($q) {
            $q->where('is_shown', true)->orderBy('order');
        }])->get();

        $authorizationTypes = AuthorizationType::all();

        $existing = DB::table('authorizations')
            ->where('role_id', $role->id)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('module_id');

        return view('admin.roles.authorizations', compact(
            'role', 'moduleGroups', 'authorizationTypes', 'existing'
        ));
    }

    public function update(Request $request, Role $role)
    {
        $authorizations = $request->input('authorizations', []);

        DB::transaction(function () use ($role, $authorizations) {
            $role->authorizations()->delete();

            $records = [];
            foreach ($authorizations as $moduleId => $typeIds) {
                foreach ((array) $typeIds as $typeId) {
                    $records[] = [
                        'role_id' => $role->id,
                        'module_id' => $moduleId,
                        'authorization_type_id' => $typeId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (! empty($records)) {
                DB::table('authorizations')->insert($records);
            }
        });

        Log::info('Authorization updated', [
            'admin_id' => auth('admin')->id(),
            'role_id' => $role->id,
        ]);

        return back()->with('success', 'Otorisasi berhasil disimpan.');
    }
}
