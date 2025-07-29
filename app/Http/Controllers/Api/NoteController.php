<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Resources\NoteResource;

class NoteController extends Controller
{
    public function __construct()
    {
        // Apply policy to all methods
        $this->authorizeResource(Note::class, 'note');
    }

    public function index(Request $request): JsonResponse
    {
        // Admins see only their notes. Super-admin sees all.
        $notes = $request->user()->hasRole('super-admin')
            ? Note::with('user')->latest()->get()
            : $request->user()->notes()->latest()->get();

        return NoteResource::collection($notes)->response();
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note = $request->user()->notes()->create($validated);

        return (new NoteResource($note))
                ->response()
                ->setStatusCode(201);
    }

    public function show(Note $note): JsonResponse
    {
        return (new NoteResource($note->load('user')))->response();
    }

    public function update(Request $request, Note $note): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note->update($validated);
        return (new NoteResource($note))->response();
    }

    public function destroy(Note $note): JsonResponse
    {
        $note->delete();
        return response()->json(null, 204);
    }
}