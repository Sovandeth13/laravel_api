<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    // List all users
    public function users()
    {
        return response()->json(User::all());
    }

    // Promote a user to admin
    public function makeAdmin($id)
    {
        $user = User::findOrFail($id);
        if ($user->is_admin) {
            return response()->json(['message' => 'User is already an admin.'], 400);
        }
        $user->is_admin = true;
        $user->save();
        return response()->json(['message' => 'User promoted to admin.']);
    }

    // Demote admin to normal user
    public function removeAdmin($id)
    {
        $user = User::findOrFail($id);
        if (!$user->is_admin) {
            return response()->json(['message' => 'User is not an admin.'], 400);
        }
        $user->is_admin = false;
        $user->save();
        return response()->json(['message' => 'Admin rights removed.']);
    }
}
