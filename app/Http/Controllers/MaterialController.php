<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with('user');

        if ($request->filled('college')) {
            $query->where('faculty', $request->input('college'));
        }

        if ($request->filled('major')) {
            $query->where('major', $request->input('major'));
        }

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }

        $materials = $query->latest()->get()->map(function ($material) {
            return [
                'id' => $material->id,
                'faculty' => $material->faculty,
                'major' => $material->major,
                'title' => $material->title,
                'note' => $material->note,
                'file_path' => $material->file_path,
                'file_type' => $material->file_type,
                'created_at' => $material->created_at,
                'user_id' => $material->user_id,
                'user_name' => $material->user?->name ?? 'Unknown',
            ];
        });

        return Inertia::render('Materials', [
            'materials' => $materials,
            'filters' => $request->only(['college', 'major', 'query']),
            'auth_user_id' => auth()->id()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'faculty' => 'required|string',
            'major' => 'required|string',
            'title' => 'required|string',
            'note' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png'
        ]);

        $file = $request->file('file');
        $path = $file->store('materials', 'public');

        Material::create([
            'faculty' => $request->faculty,
            'major' => $request->major,
            'title' => $request->title,
            'note' => $request->note,
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Material uploaded successfully.');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);

        if ($material->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return back();
    }
}