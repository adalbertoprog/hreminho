<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'department'  => 'required|string|max:100|unique:departments,department',
            'description' => 'nullable|string|max:255',
            'manager_id'  => 'nullable|exists:employees,id',
        ];
    }
}
