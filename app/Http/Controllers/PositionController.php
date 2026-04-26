<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\StorePositionRequest;
use App\Http\Requests\Position\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PositionController extends Controller
{
    /**
     * Listar todos os cargos.
     */
    public function index(): AnonymousResourceCollection
    {
        $positions = Position::orderBy('position')->get();

        return PositionResource::collection($positions);
    }

    /**
     * Criar um novo cargo.
     */
    public function store(StorePositionRequest $request): PositionResource
    {
        $position = Position::create($request->validated());

        return new PositionResource($position);
    }

    /**
     * Exibir um cargo específico.
     */
    public function show(Position $position): PositionResource
    {
        return new PositionResource($position);
    }

    /**
     * Atualizar um cargo.
     */
    public function update(UpdatePositionRequest $request, Position $position): PositionResource
    {
        $position->update($request->validated());

        return new PositionResource($position->fresh());
    }

    /**
     * Remover um cargo.
     */
    public function destroy(Position $position): JsonResponse
    {
        if ($position->employees()->exists()) {
            return response()->json([
                'message' => 'Não é possível excluir este cargo pois existem funcionários vinculados a ele.',
            ], 422);
        }

        $position->delete();

        return response()->json(['message' => 'Cargo excluído com sucesso.'], 200);
    }
}
