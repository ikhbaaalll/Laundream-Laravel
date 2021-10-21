<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaundryStoreRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->tokenCan('laundry.create');
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email', Rule::unique('users')],
            'name' => ['required'],
            'password' => ['required'],

            'laundry' => ['required'],
        ];
    }
}
