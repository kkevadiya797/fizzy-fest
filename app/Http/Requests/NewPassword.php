<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'newPassword' => 'required',
            'confirmPassword' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required',
        ];
    }
}
