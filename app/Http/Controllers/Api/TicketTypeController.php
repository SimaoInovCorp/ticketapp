<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketType\StoreTicketTypeRequest;
use App\Http\Requests\TicketType\UpdateTicketTypeRequest;
use App\Http\Resources\TicketTypeResource;
use App\Models\TicketType;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketTypeController extends Controller
{
    public function __construct(private readonly TicketService $ticketService) {}

    public function index(): AnonymousResourceCollection
    {
        return TicketTypeResource::collection($this->ticketService->allTypes());
    }

    public function store(StoreTicketTypeRequest $request): TicketTypeResource
    {
        return new TicketTypeResource($this->ticketService->createType($request->validated()));
    }

    public function update(UpdateTicketTypeRequest $request, TicketType $ticketType): TicketTypeResource
    {
        return new TicketTypeResource($this->ticketService->updateType($ticketType, $request->validated()));
    }

    public function destroy(TicketType $ticketType): JsonResponse
    {
        abort_unless(auth()->user()?->isOperator(), 403);
        $this->ticketService->deleteType($ticketType);

        return response()->json(null, 204);
    }
}
