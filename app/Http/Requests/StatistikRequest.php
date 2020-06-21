<?php

namespace App\Http\Requests;

use App\Services\Mapper\Facades\Mapper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StatistikRequest extends FormRequest
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
            'personal_investor' => 'required|numeric|min:0',
            'company_investor' => 'required|numeric|min:0',
            'total_goods' => 'required|numeric|min:0',
            'total_cash' => 'required|numeric|min:0'
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
        throw new HttpResponseException(Mapper::validation($validator, $this->method()));
    }
}