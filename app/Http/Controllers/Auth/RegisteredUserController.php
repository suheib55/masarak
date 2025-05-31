<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response//1
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse//2
    {
        $request->validate([//3
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                function ($attribute, $value, $fail) {
                    if (
                        !str_ends_with($value, '@just.edu.jo') &&
                        !str_ends_with($value, '@cit.just.edu.jo') &&
                        $value !== 'smhmwdabwalshykh@gmail.com'
                    ) {
                        $fail('Only university emails (@just.edu.jo or @cit.just.edu.jo) are allowed, except for the authorized admin email.');
                    }
                }
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',     
                'regex:/[A-Z]/',      
                'regex:/[0-9]/',    
                'regex:/[@$!%*#?&]/', 
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

      
        $email = $request->email;
        $role = 'student';

        if ($email === 'smhmwdabwalshykh@gmail.com') {
            $role = 'admin';
        } elseif (str_ends_with($email, '@gmail.com')) {
            return back()->withErrors(['email' => 'Only the authorized admin can use a Gmail account.']);
        } elseif (str_ends_with($email, '@just.edu.jo')) {
            $role = 'instructor';
        } elseif (str_ends_with($email, '@cit.just.edu.jo')) {
            $role = 'student';
        }

        $user = User::create([//4
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),//6
            'role' => $role,
            'faculty' => $request->faculty,
            'major' => $request->major,
        ]);

        

        return redirect(RouteServiceProvider::HOME);
    }
}