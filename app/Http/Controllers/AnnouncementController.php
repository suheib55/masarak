<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderByDesc('created_at')->get();

        return Inertia::render('Announcements', [
            'announcements' => $announcements,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'posted_by' => auth()->user()->email,
        ]);

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->posted_by !== auth()->user()->email) {
            abort(403, 'You are not allowed to update this announcement.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->posted_by !== auth()->user()->email) {
            abort(403, 'You are not allowed to delete this announcement.');
        }

        $announcement->delete();

        return redirect()->back();
    }
}