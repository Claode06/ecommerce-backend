<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileStorage;
use Illuminate\View\View;

class FileStorageController extends Controller
{
    public function index(): View
    {
        $files = FileStorage::orderBy('created_at', 'desc')->paginate(25);

        return view('admin.file-storages.index', compact('files'));
    }
}
