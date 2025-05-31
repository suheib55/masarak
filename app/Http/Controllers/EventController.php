<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AnnouncedLink;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'date' => $event->date,
                'location' => $event->location,
                'note' => $event->note,
                'image_url' => $event->image 
                    ? asset('storage/' . $event->image) 
                    : 'https://data.textstudio.com/output/sample/animated/6/3/2/5/event-3-5236.gif',
                'created_by_name' => $event->created_by_name,
                'created_by_email' => $event->created_by_email,
            ];
        });

        return Inertia::render('Events', [
            'events' => $events,
            'user' => auth()->user(),
            'announcedLinks' => AnnouncedLink::latest()->pluck('link'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'date' => 'required|date',
            'location' => 'nullable|string',
            'note' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        } else {
            $data['image'] = null;
        }

        $data['created_by_name'] = auth()->user()->name ?? 'Anonymous';
        $data['created_by_email'] = auth()->user()->email ?? 'unknown@example.com';

        Event::create($data);

        return redirect()->back();
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'student_id' => 'required|string',
        ]);

        EventRegistration::create($data);

        return redirect()->back();
    }
}