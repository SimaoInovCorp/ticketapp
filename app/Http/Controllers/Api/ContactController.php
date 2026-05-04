<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContactController extends Controller
{
    public function __construct(private readonly ContactService $contactService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Contact::class);

        return ContactResource::collection($this->contactService->paginate($request->all()));
    }

    public function store(StoreContactRequest $request): ContactResource
    {
        $data = $request->safe()->except('entity_ids');
        $contact = $this->contactService->create($data, $request->input('entity_ids', []));

        return new ContactResource($contact);
    }

    public function show(Contact $contact): ContactResource
    {
        $this->authorize('view', $contact);

        return new ContactResource($contact->load(['role', 'entities']));
    }

    public function update(UpdateContactRequest $request, Contact $contact): ContactResource
    {
        $this->authorize('update', $contact);
        $data = $request->safe()->except('entity_ids');
        $updated = $this->contactService->update($contact, $data, $request->input('entity_ids', []));

        return new ContactResource($updated);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        $this->authorize('delete', $contact);
        $this->contactService->delete($contact);

        return response()->json(null, 204);
    }
}
