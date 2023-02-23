<?php

namespace App\Http\Requests\Admin\Department;

use App\Http\Requests\AdminCoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // If admin
        return admin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'deptName'   =>  'required|unique:departments,deptName,:id,id,company_id,' . $this->companyId,
        ];
    }

    public function messages()
    {
        return [
            'deptName.required' => 'The department name field is required.',
        ];
    }
}
