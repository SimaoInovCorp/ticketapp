<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketMessage\StoreTicketMessageRequest;
use App\Http\Resources\TicketMessageResource;
use App\Models\Ticket;
use App\Services\TicketMessageService;

class TicketMessageController extends Controller
{
    public function __construct(private readonly TicketMessageService $messageService) {}

    public function store(StoreTicketMessageRequest $request, Ticket $ticket): TicketMessageResource
    {
        $this->authorize('view', $ticket);

        $message = $this->messageService->addMessage(
            $ticket,
            $request->user(),
            $request->safe()->except('attachments'),
            $request->file('attachments', []),
        );

        return new TicketMessageResource($message->load(['author', 'attachments']));
    }
}
