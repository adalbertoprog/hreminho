<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('employee')?->id ?? $this->route('employee');

        return [
            'code'          => "sometimes|string|max:50|unique:employees,code,{$id}",
            'first_name'    => 'sometimes|string|max:100',
            'last_name'     => 'sometimes|string|max:100',
            'email'         => "sometimes|email|max:150|unique:employees,email,{$id}",
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:male,female,other',
            'nationality'   => 'nullable|string|max:100',
            'address'       => 'nullable|string|max:255',
            'work_location' => 'nullable|string|max:200',
            'photo'         => 'nullable|string',
            'position_id'   => 'sometimes|exists:positions,id',
            'department_id' => 'sometimes|exists:departments,id',
            'sector_id'     => 'nullable|exists:sectors,id',
            'hire_date'     => 'sometimes|date',
            'status'        => 'nullable|in:active,inactive,terminated',
            'contract_type' => 'nullable|string|max:50',
            'end_date'      => 'nullable|date|after_or_equal:hire_date',
        ];
    }
}
