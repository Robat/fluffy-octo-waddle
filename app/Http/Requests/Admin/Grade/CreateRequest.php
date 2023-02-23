<?php

namespace App\Http\Requests\Admin\Grade;

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
            'grade'   =>  'required',
        ];
    }

    public function messages()
    {
        return [
            'grade.required' => 'The grade name field is required.',
        ];
    }
}
