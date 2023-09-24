<?php

namespace App\Repositories;

use App\Interfaces\ThoughtRepositoryInterface;
use App\Models\Thought;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ThoughtRepository implements ThoughtRepositoryInterface
{
    public function getThoughts(): Collection
    {
        return Thought::orderBy('created_at', 'desc')->get();
    }

    public function createThought(Request $request, string|null $fileName): void
    {
        Thought::create([
            'user_id' => auth()->id(),
            'category_id' => $request->get('category_id'),
            'photo' => $fileName,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'type' => $request->get('type'),
        ]);
    }

    public function updateThought(Thought $thought, Request $request, string $fileName): void
    {
        $thought->update([
            'category_id' => $request->get('category_id'),
            'photo' => $fileName,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'type' => $request->get('type'),
        ]);
    }

    public function getThought(int $id): Thought|null
    {
        return Thought::find($id);
    }

    public function deleteThought(Thought $thought): bool
    {
        return $thought->delete();
    }
}
