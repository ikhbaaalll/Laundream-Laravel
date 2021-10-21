<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->tokenCan('employee.create');
    }

    public function rules()
    {
        return [
            'status' => ['required', 'in:1,0']
        ];
    }
}
