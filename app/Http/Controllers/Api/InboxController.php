<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inbox\StoreInboxRequest;
use App\Http\Requests\Inbox\UpdateInboxRequest;
use App\Http\Resources\InboxResource;
use App\Models\Inbox;
use App\Services\InboxService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InboxController extends Controller
{
    public function __construct(private readonly InboxService $inboxService) {}

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Inbox::class);

        return InboxResource::collection($this->inboxService->all());
    }

    public function store(StoreInboxRequest $request): InboxResource
    {
        $inbox = $this->inboxService->create($request->validated());

        return new InboxResource($inbox);
    }

    public function show(Inbox $inbox): InboxResource
    {
        return new InboxResource($inbox->loadCount('operators'));
    }

    public function update(UpdateInboxRequest $request, Inbox $inbox): InboxResource
    {
        return new InboxResource($this->inboxService->update($inbox, $request->validated()));
    }

    public function destroy(Inbox $inbox): JsonResponse
    {
        $this->authorize('delete', $inbox);
        $this->inboxService->delete($inbox);

        return response()->json(null, 204);
    }

    public function syncOperators(Request $request, Inbox $inbox): JsonResponse
    {
        $this->authorize('syncOperators', $inbox);
        $validated = $request->validate(['user_ids' => ['required', 'array'], 'user_ids.*' => ['exists:users,id']]);
        $this->inboxService->syncOperators($inbox, $validated['user_ids']);

        return response()->json(['message' => 'Operators synced.']);
    }
}
