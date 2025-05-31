<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\RidePassenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RideController extends Controller
{
    public function index()
    {
        $rides = Ride::with(['user', 'passengers'])->latest()->get();

        return Inertia::render('Rideshare', [
            'rides' => $rides,
            'auth' => [
                'user' => Auth::user(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'time' => 'required',
            'seats' => 'required|integer|min:1',
            'phone' => 'required|string|max:20',
        ]);

        Ride::create([
            'user_id' => auth()->id(),
            'city' => $request->city,
            'area' => $request->area,
            'time' => $request->time,
            'seats' => $request->seats,
            'phone' => $request->phone,
        ]);

        return redirect()->back();
    }

    public function join(Request $request)
    {
        $ride = Ride::find($request->ride_id);

        if (!$ride || $ride->seats <= 0) {
            return redirect()->back()->withErrors(['message' => 'No seats available']);
        }

        RidePassenger::create([
            'ride_id' => $ride->id,
            'user_id' => auth()->id(),
            'passenger_name' => $request->passenger_name,
            'phone' => $request->phone,
        ]);

        $ride->decrement('seats'); // خصم مقعد واحد
        return redirect()->back()->with('success', 'You joined the ride successfully.');
    }

    public function removePassenger($id)
    {
        $passenger = RidePassenger::findOrFail($id);
        $ride = $passenger->ride;

        if ($ride->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $passenger->delete();
        $ride->increment('seats'); 
        return back()->with('success', 'Passenger removed.');
    }

    public function deleteRide($id)
    {
        $ride = Ride::findOrFail($id);

        if ($ride->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $ride->delete();
        return back()->with('success', 'Ride deleted.');
    }
}