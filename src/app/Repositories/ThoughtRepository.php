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

    public function createThought(array $request, string|null $fileName): void
    {
        Thought::create([
            'user_id' => auth()->id(),
            'category_id' => $request['category_id'],
            'photo' => $fileName,
            'title' => $request['title'],
            'content' => $request['content'],
            'type' => $request['type'],
        ]);
    }

    public function updateThought(Thought $thought, array $request, string $fileName): void
    {
        $thought->update([
            'category_id' => $request['category_id'],
            'photo' => $fileName,
            'title' => $request['title'],
            'content' => $request['content'],
            'type' => $request['type'],
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
