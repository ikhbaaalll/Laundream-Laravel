<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\EmployeeStoreRequest;
use App\Http\Requests\Owner\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use App\Models\Laundry;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    public function index(Laundry $laundry)
    {
        throw_if(
            auth()->id() != $laundry->user_id || !auth()->user()->tokenCan('employee.list'),
            ValidationException::withMessages(['employee' => 'Tidak dapat melihat karyawan!'])
        );

        $employee = Employee::query()
            ->whereBelongsTo($laundry)
            ->with('user')
            ->get();

        return EmployeeResource::collection($employee);
    }

    public function store(EmployeeStoreRequest $employeeStoreRequest, Laundry $laundry)
    {
        throw_if(
            auth()->id() != $laundry->user_id,
            ValidationException::withMessages(['employee' => 'Tidak dapat membuat karyawan!'])
        );

        $employee = User::create(
            [
                'name'      => $employeeStoreRequest->name,
                'email'     => $employeeStoreRequest->email,
                'password'  => bcrypt($employeeStoreRequest->no_hp),
                'role'      => User::ROLE_EMPLOYEE,
                'no_hp'     => $employeeStoreRequest->no_hp
            ]
        );

        $laundry->employees()->create([
            'user_id' => $employee->id,
        ]);

        return UserResource::make($employee);
    }

    public function update(EmployeeUpdateRequest $employeeUpdateRequest, Laundry $laundry, Employee $employee)
    {
        throw_if(
            auth()->id() != $laundry->user_id
                || $laundry->id != $employee->laundry_id,
            ValidationException::withMessages(['employee' => 'Tidak dapat mengubah karyawan!'])
        );

        User::where('id', $employee->user_id)
            ->update($employeeUpdateRequest->validated());

        $employee->load('user');

        return EmployeeResource::make($employee);
    }

    public function destroy(Laundry $laundry, Employee $employee)
    {
        throw_if(
            !auth()->user()->tokenCan('employee.delete')
                || auth()->id() != $laundry->user_id
                || $laundry->id != $employee->laundry_id,
            ValidationException::withMessages(['employee' => 'Tidak dapat menghapus karyawan!'])
        );

        User::find($employee->user_id)->delete();
    }
}
