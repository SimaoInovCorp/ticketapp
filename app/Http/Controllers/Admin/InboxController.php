<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\InboxResource;
use App\Services\InboxService;
use Inertia\Inertia;
use Inertia\Response;

class InboxController extends Controller
{
    public function __construct(private readonly InboxService $inboxService) {}

    public function index(): Response
    {
        abort_unless(auth()->user()?->isOperator(), 403);

        return Inertia::render('admin/inboxes/Index', [
            'inboxes'   => InboxResource::collection($this->inboxService->all()),
            'operators' => $this->inboxService->allOperators(),
        ]);
    }
}
