<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
{
    // تحقق من الصورة
    $request->validate([
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
    ]);

    $user = $request->user();

    // حذف الصورة القديمة إذا كانت موجودة
    if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
        \Storage::disk('public')->delete($user->profile_image);
    }

    // تخزين الصورة الجديدة
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('profile_images', 'public');
        $user->profile_image = $imagePath;
    }

    $user->save();

    return redirect()->route('profile.edit')->with('success', 'Profile image updated successfully.');
}
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}