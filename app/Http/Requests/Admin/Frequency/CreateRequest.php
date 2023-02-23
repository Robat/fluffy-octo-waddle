<?php

namespace App\Http\Requests\Admin\Frequency;

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
            'frequency_name'   =>  'required',
            'start_at'   =>  'required',
            'ends_at'   =>  'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The salary name field is required.',
        ];
    }
}