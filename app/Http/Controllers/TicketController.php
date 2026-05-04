<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Models\Ticket;
use App\Services\ContactService;
use App\Services\InboxService;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
        private readonly InboxService $inboxService,
        private readonly ContactService $contactService,
    ) {}

    public function index(Request $request): Response
    {
        $tickets = $this->ticketService->paginate($request->user(), $request->all());

        return Inertia::render('tickets/Index', [
            'tickets'   => \App\Http\Resources\TicketResource::collection($tickets),
            'inboxes'   => $this->inboxService->allSimple(),
            'statuses'  => $this->ticketService->allStatuses(),
            'types'     => $this->ticketService->allTypes(),
            'operators' => $this->inboxService->allOperators(),
            'filters'   => $request->only(['inbox_id', 'ticket_status_id', 'operator_id', 'ticket_type_id', 'entity_id', 'search']),
        ]);
    }

    public function show(Ticket $ticket): Response
    {
        $this->authorize('view', $ticket);

        $ticket->load([
            'inbox', 'status', 'type', 'operator', 'entity', 'contact', 'knowledgeEmails', 'createdBy',
            'messages' => fn ($q) => $q->with(['author', 'attachments']),
            'activityLogs' => fn ($q) => $q->with('user'),
        ]);

        return Inertia::render('tickets/Show', [
            'ticket'    => new \App\Http\Resources\TicketResource($ticket),
            'operators' => $this->inboxService->allOperators(),
            'statuses'  => $this->ticketService->allStatuses(),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Ticket::class);

        return Inertia::render('tickets/Create', [
            'inboxes'   => $this->inboxService->allSimple(),
            'types'     => $this->ticketService->allTypes(),
            'operators' => $this->inboxService->allOperators(),
            'contacts'  => $this->contactService->allSimple(),
        ]);
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $this->authorize('create', Ticket::class);

        $data               = $request->validated();
        $data['attachments'] = $request->file('attachments', []);

        $ticket = $this->ticketService->create($request->user(), $data);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', "Ticket {$ticket->number} created successfully.");
    }

    public function edit(Ticket $ticket): Response
    {
        $this->authorize('update', $ticket);

        $ticket->load(['type', 'knowledgeEmails']);

        return Inertia::render('tickets/Edit', [
            'ticket' => new \App\Http\Resources\TicketResource($ticket),
            'types'  => $this->ticketService->allTypes(),
        ]);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $this->ticketService->update($ticket, $request->validated());

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->authorize('delete', $ticket);

        $this->ticketService->delete($ticket);

        return redirect()->route('tickets.index')
            ->with('success', "Ticket {$ticket->number} deleted.");
    }
}
