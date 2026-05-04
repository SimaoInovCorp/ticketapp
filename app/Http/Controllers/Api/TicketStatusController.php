<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketStatus\StoreTicketStatusRequest;
use App\Http\Requests\TicketStatus\UpdateTicketStatusRequest;
use App\Http\Resources\TicketStatusResource;
use App\Models\TicketStatus;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketStatusController extends Controller
{
    public function __construct(private readonly TicketService $ticketService) {}

    public function index(): AnonymousResourceCollection
    {
        return TicketStatusResource::collection($this->ticketService->allStatuses());
    }

    public function store(StoreTicketStatusRequest $request): TicketStatusResource
    {
        return new TicketStatusResource($this->ticketService->createStatus($request->validated()));
    }

    public function update(UpdateTicketStatusRequest $request, TicketStatus $ticketStatus): TicketStatusResource
    {
        return new TicketStatusResource($this->ticketService->updateStatus($ticketStatus, $request->validated()));
    }

    public function destroy(TicketStatus $ticketStatus): JsonResponse
    {
        abort_unless(auth()->user()?->isOperator(), 403);
        $this->ticketService->deleteStatus($ticketStatus);

        return response()->json(null, 204);
    }
}
