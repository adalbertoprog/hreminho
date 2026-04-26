<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'          => 'required|string|max:50|unique:employees,code',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|max:150|unique:employees,email',
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:male,female,other',
            'nationality'   => 'nullable|string|max:100',
            'address'       => 'nullable|string|max:255',
            'work_location' => 'nullable|string|max:200',
            'position_id'   => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'sector_id'     => 'nullable|exists:sectors,id',
            'hire_date'     => 'required|date',
            'status'        => 'nullable|in:active,inactive,terminated',
            'contract_type' => 'nullable|string|max:50',
            'end_date'      => 'nullable|date|after_or_equal:hire_date',
        ];
    }
}
