<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\ContactService;
use App\Services\EntityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContactService $contactService,
        private readonly EntityService $entityService,
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Contact::class);

        return Inertia::render('contacts/Index', [
            'contacts' => ContactResource::collection($this->contactService->paginate($request->all())),
            'filters'  => $request->only(['search', 'entity_id']),
        ]);
    }

    public function show(Contact $contact): Response
    {
        $this->authorize('view', $contact);

        $contact->load(['role', 'entities', 'user']);

        return Inertia::render('contacts/Show', [
            'contact' => new ContactResource($contact),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Contact::class);

        return Inertia::render('contacts/Create', [
            'roles'    => $this->contactService->allRoles(),
            'entities' => $this->entityService->allSimple(),
        ]);
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $data      = $request->safe()->except('entity_ids');
        $contact   = $this->contactService->create($data, $request->input('entity_ids', []));

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Contact created successfully.');
    }

    public function edit(Contact $contact): Response
    {
        $this->authorize('update', $contact);

        $contact->load(['role', 'entities']);

        return Inertia::render('contacts/Edit', [
            'contact'  => new ContactResource($contact),
            'roles'    => $this->contactService->allRoles(),
            'entities' => $this->entityService->allSimple(),
        ]);
    }

    public function update(UpdateContactRequest $request, Contact $contact): RedirectResponse
    {
        $data = $request->safe()->except('entity_ids');
        $this->contactService->update($contact, $data, $request->input('entity_ids', []));

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $this->authorize('delete', $contact);
        $this->contactService->delete($contact);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }
}
