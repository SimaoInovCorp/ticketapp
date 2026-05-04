<?php

namespace App\Http\Controllers;

use App\Http\Requests\Entity\StoreEntityRequest;
use App\Http\Requests\Entity\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Http\Resources\TicketResource;
use App\Models\Entity;
use App\Services\EntityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EntityController extends Controller
{
    public function __construct(private readonly EntityService $entityService) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Entity::class);

        return Inertia::render('entities/Index', [
            'entities' => EntityResource::collection($this->entityService->paginate($request->all())),
            'filters'  => $request->only(['search']),
        ]);
    }

    public function show(Entity $entity): Response
    {
        $this->authorize('view', $entity);

        $entity->load(['contacts.role', 'contacts.entities']);

        return Inertia::render('entities/Show', [
            'entity'  => new EntityResource($entity),
            'tickets' => TicketResource::collection(
                $this->entityService->recentTickets($entity)
            ),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Entity::class);

        return Inertia::render('entities/Create');
    }

    public function store(StoreEntityRequest $request): RedirectResponse
    {
        $entity = $this->entityService->create($request->validated());

        return redirect()->route('entities.show', $entity)
            ->with('success', 'Entity created successfully.');
    }

    public function edit(Entity $entity): Response
    {
        $this->authorize('update', $entity);

        return Inertia::render('entities/Edit', [
            'entity' => new EntityResource($entity),
        ]);
    }

    public function update(UpdateEntityRequest $request, Entity $entity): RedirectResponse
    {
        $this->entityService->update($entity, $request->validated());

        return redirect()->route('entities.show', $entity)
            ->with('success', 'Entity updated successfully.');
    }

    public function destroy(Entity $entity): RedirectResponse
    {
        $this->authorize('delete', $entity);
        $this->entityService->delete($entity);

        return redirect()->route('entities.index')
            ->with('success', 'Entity deleted successfully.');
    }
}
