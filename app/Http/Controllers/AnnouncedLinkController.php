<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnnouncedLink;

class AnnouncedLinkController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'link' => 'required|url|max:2048',
        ]);

        AnnouncedLink::create($validated);

        return redirect()->back()->with('message', 'Link announced successfully.');
    }
}