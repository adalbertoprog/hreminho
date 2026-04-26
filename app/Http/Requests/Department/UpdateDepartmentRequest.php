<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('department')?->id ?? $this->route('department');

        return [
            'department'  => "sometimes|string|max:100|unique:departments,department,{$id}",
            'description' => 'nullable|string|max:255',
            'manager_id'  => 'nullable|exists:employees,id',
        ];
    }
}
