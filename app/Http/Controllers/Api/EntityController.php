<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Entity\StoreEntityRequest;
use App\Http\Requests\Entity\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use App\Services\EntityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EntityController extends Controller
{
    public function __construct(private readonly EntityService $entityService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Entity::class);

        return EntityResource::collection($this->entityService->paginate($request->all()));
    }

    public function store(StoreEntityRequest $request): EntityResource
    {
        return new EntityResource($this->entityService->create($request->validated()));
    }

    public function show(Entity $entity): EntityResource
    {
        $this->authorize('view', $entity);

        return new EntityResource($entity->load('contacts'));
    }

    public function update(UpdateEntityRequest $request, Entity $entity): EntityResource
    {
        $this->authorize('update', $entity);

        return new EntityResource($this->entityService->update($entity, $request->validated()));
    }

    public function destroy(Entity $entity): JsonResponse
    {
        $this->authorize('delete', $entity);
        $this->entityService->delete($entity);

        return response()->json(null, 204);
    }
}
