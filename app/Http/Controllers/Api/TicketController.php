<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\AssignTicketRequest;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\NotificationService;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
        private readonly NotificationService $notificationService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $tickets = $this->ticketService->paginate($request->user(), $request->all());

        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request): TicketResource
    {
        $this->authorize('create', Ticket::class);
        $ticket = $this->ticketService->create($request->user(), $request->validated());

        return new TicketResource($ticket);
    }

    public function show(Ticket $ticket): TicketResource
    {
        $this->authorize('view', $ticket);
        $ticket->load(['inbox', 'status', 'type', 'operator', 'entity', 'contact', 'knowledgeEmails', 'createdBy',
            'messages' => fn ($q) => $q->with(['author', 'attachments']),
            'activityLogs' => fn ($q) => $q->with('user'),
        ]);

        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): TicketResource
    {
        $this->authorize('update', $ticket);

        $updated = $this->ticketService->update($ticket, $request->validated());
        $updated->load(['inbox', 'status', 'type', 'operator', 'entity', 'contact']);

        return new TicketResource($updated);
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $this->authorize('delete', $ticket);
        $this->ticketService->delete($ticket);

        return response()->json(null, 204);
    }

    public function assign(AssignTicketRequest $request, Ticket $ticket): TicketResource
    {
        $this->authorize('assign', $ticket);
        $updated = $this->ticketService->assignOperator($ticket, $request->input('operator_id'), $request->user());
        $updated->load(['inbox', 'status', 'type', 'operator', 'entity', 'contact']);

        return new TicketResource($updated);
    }

    public function changeStatus(Request $request, Ticket $ticket): TicketResource
    {
        $this->authorize('update', $ticket);
        $validated = $request->validate(['status_id' => ['required', 'exists:ticket_statuses,id']]);
        $updated = $this->ticketService->changeStatus($ticket, $validated['status_id'], $request->user());
        $updated->load(['inbox', 'status', 'type', 'operator', 'entity', 'contact']);

        return new TicketResource($updated);
    }

    public function testEmail(Request $request, Ticket $ticket): JsonResponse
    {
        $this->authorize('view', $ticket);

        $ticket->load(['inbox', 'status', 'type', 'operator', 'entity', 'contact']);
        $this->notificationService->sendTestEmail($ticket, $request->user()->email);

        return response()->json(['message' => 'Test email sent to ' . $request->user()->email]);
    }
}
