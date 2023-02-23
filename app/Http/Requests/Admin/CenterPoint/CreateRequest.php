<?php

namespace App\Http\Requests\Admin\CenterPoint;

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
            'name'   =>  'required|nullable|max:50',
            'numbering'  => 'required|nullable|numeric',
            'location' => 'required',
            'isCover' => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'numbering.required' => 'The number field is required.',
            'location.required' => '請選擇中間值',
            'isCover.required' => '請確認是否需要覆蓋',
        ];
    }
}
