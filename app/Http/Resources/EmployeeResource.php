<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'code'          => $this->code,
            'full_name'     => $this->full_name,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'gender'        => $this->gender,
            'nationality'   => $this->nationality,
            'address'       => $this->address,
            'work_location' => $this->work_location,
            'photo'         => $this->profile_photo,
            'hire_date'     => $this->hire_date?->toDateString(),
            'end_date'      => $this->end_date?->toDateString(),
            'status'        => $this->status,
            'contract_type' => $this->contract_type,
            'position'      => $this->whenLoaded('position', fn() => [
                'id'       => $this->position->id,
                'position' => $this->position->position,
            ]),
            'department'    => $this->whenLoaded('department', fn() => [
                'id'         => $this->department->id,
                'department' => $this->department->department,
            ]),
            'sector'        => $this->whenLoaded('sector', fn() => [
                'id'     => $this->sector->id,
                'sector' => $this->sector->sector ?? $this->sector->name ?? null,
            ]),
            'created_at'    => $this->created_at?->toDateTimeString(),
            'updated_at'    => $this->updated_at?->toDateTimeString(),
        ];
    }
}
