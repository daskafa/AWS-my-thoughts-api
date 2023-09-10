<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\ThoughtRequest;
use App\Services\ThoughtService;

class ThoughtController extends Controller
{
    private ThoughtService $thoughtService;

    public function __construct(ThoughtService $thoughtService)
    {
        $this->thoughtService = $thoughtService;
    }

    public function index()
    {
        return $this->thoughtService->getThoughts();
    }

    public function store(ThoughtRequest $request)
    {
        return $this->thoughtService->createThought($request);
    }

    public function show(string $id)
    {
        return $this->thoughtService->getThought($id);
    }

    public function update(ThoughtRequest $request, string $id)
    {
        return $this->thoughtService->updateThought($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->thoughtService->deleteThought($id);
    }
}
