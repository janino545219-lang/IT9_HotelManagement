<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalkIn;

class WalkInController extends Controller
{
    public function index()
    {
        $walkins = WalkIn::with('employee')->latest()->paginate(5);
        return view('admin.walkins', compact('walkins'));
    }
}