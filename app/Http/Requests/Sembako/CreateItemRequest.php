<?php

namespace App\Http\Requests\Sembako;

use App\Libraries\ResponseLibrary;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateItemRequest extends FormRequest
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
            'item_name' => 'required|min:3|max:150',
            'item_sku' => 'required|min:5|max:50|unique:sembako_package_items,item_sku,NULL,id',
            'quantity' => 'required|digits_between:1,9999999999',
            'package_description' => 'required|min:6',
            'status' => 'boolean'
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
        throw new HttpResponseException($responseLib->validationFailJsonResponse($validator->errors()->all()), 422);
    }
}
