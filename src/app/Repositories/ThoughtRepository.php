<?php

namespace App\Repositories;

use App\Interfaces\ThoughtRepositoryInterface;
use App\Models\Thought;

class ThoughtRepository implements ThoughtRepositoryInterface
{
    public function getThoughts()
    {
        return Thought::orderBy('created_at', 'desc')->get();
    }

    public function createThought($request, $fileName): void
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

    public function updateThought($thought, $request, $fileName): void
    {
        $thought->update([
            'category_id' => $request->get('category_id'),
            'photo' => $fileName,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'type' => $request->get('type'),
        ]);
    }

    public function getThought(int $id)
    {
        return Thought::find($id);
    }

    public function deleteThought($thought): void
    {
        $thought->delete();
    }
}
