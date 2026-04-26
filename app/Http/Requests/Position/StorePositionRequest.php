<?php

namespace App\Http\Requests\Position;

use Illuminate\Foundation\Http\FormRequest;

class StorePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'position'    => 'required|string|max:100|unique:positions,position',
            'description' => 'nullable|string|max:255',
        ];
    }
}
