<?php

namespace App\Http\Requests\Admin\Employee;

use App\Http\Requests\AdminCoreRequest;
use App\Models\Employee;

use Illuminate\Validation\Rule;

class UpdateRequest extends AdminCoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // dd($this->route('employee'));
        $employee = Employee::where('employeeID', $this->route('employee'));

        return admin() && $employee;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $rules['email'] = [
            'required',
            'email',
            Rule::unique('employees')->ignore($this->route('employee'), 'id'),
        ];



        return $rules;
    }
}
