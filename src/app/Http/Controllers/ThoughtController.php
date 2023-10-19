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
        $this->prepareAndSetStoreData($request);

        return $this->thoughtService->createThought();
    }

    public function show(int $id): JsonResponse
    {
        return $this->thoughtService->getThought($id);
    }

    public function update(ThoughtRequest $request, int $id): JsonResponse
    {
        $this->prepareAndSetUpdateData($request);

        return $this->thoughtService->updateThought($id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->thoughtService->deleteThought($id);
    }

    private function prepareAndSetStoreData(ThoughtRequest $request): void
    {
        $this->thoughtService->setStoreData(
            $request->get('category_id'),
            $request->file('photo'),
            $request->get('title'),
            $request->get('content'),
            $request->get('type')
        );
    }

    private function prepareAndSetUpdateData(ThoughtRequest $request): void
    {
        $this->thoughtService->setUpdateData(
            $request->get('category_id'),
            $request->file('photo'),
            $request->get('title'),
            $request->get('content'),
            $request->get('type')
        );
    }
}
