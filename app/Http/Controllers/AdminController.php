<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function users()
    {
        // Get all users, you can add pagination or filtering here later
        $users = User::all();

        return response()->json($users);
    }
}
