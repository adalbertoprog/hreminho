<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'department'  => $this->department,
            'description' => $this->description,
            'manager'     => $this->whenLoaded('manager', fn() => [
                'id'        => $this->manager->id,
                'full_name' => $this->manager->full_name,
            ]),
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
