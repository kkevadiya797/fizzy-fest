<?php

namespace App\Http\Requests;

use App\Rules\Iunique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class Register extends FormRequest
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
            'username' => ['required', new Iunique('users'),'regex:/^[a-zA-Z0-9_-].*$/'],
            'email' => ['required', 'email', new Iunique('users')],
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
            'userType' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required',
            'email' => ':attribute is not valid',
            'unique' => ':attribute already exists',
            'regex' => ':attribute is not valid',
            'same' => ':attrbute does not match'
        ];
    }
}
