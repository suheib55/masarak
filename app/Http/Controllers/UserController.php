<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function searchUsers(Request $request)
    {
        $searchQuery = $request->query('search');
        $users = User::where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('email', 'like', "%{$searchQuery}%")
                    ->get();

        return Inertia::render('Notifications', [
            'filteredUsers' => $users,  
        ]);
    }
}