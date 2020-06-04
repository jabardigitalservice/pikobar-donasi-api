<?php

namespace App\Http\Requests\User;

use App\Libraries\ResponseLibrary;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRequest extends FormRequest
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
            'username' => 'required|min:3|max:150',
            'email' => 'email|required|min:5|max:50|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|min:6|max:50',
            'avatar' => 'mimes:jpeg,jpg,png',
            'sex' => 'in:male,female,other',
            'roles' => 'required|array',
            'roles.*' => 'required',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $responseLib = new ResponseLibrary();
        throw new HttpResponseException( $responseLib->validationFailJsonResponse($validator->errors()->all()), 422);
    }
}
