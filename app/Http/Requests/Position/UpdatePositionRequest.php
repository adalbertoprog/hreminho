<?php

namespace App\Http\Requests\Position;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('position')?->id ?? $this->route('position');

        return [
            'position'    => "sometimes|string|max:100|unique:positions,position,{$id}",
            'description' => 'nullable|string|max:255',
        ];
    }
}
