<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\ThoughtRequest;
use App\Services\ThoughtService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ThoughtController extends Controller
{
    private ThoughtService $thoughtService;

    public function __construct(ThoughtService $thoughtService)
    {
        $this->thoughtService = $thoughtService;
    }

    public function index(): JsonResponse
    {
        return $this->thoughtService->getThoughts();
    }

    public function store(ThoughtRequest $request): JsonResponse
    {
        return $this->thoughtService->createThought($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->thoughtService->getThought($id);
    }

    public function update(ThoughtRequest $request, int $id): JsonResponse
    {
        return $this->thoughtService->updateThought($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->thoughtService->deleteThought($id);
    }
}
