<?php

namespace App\Http\Requests\Investor;

use App\Libraries\ConstantParser;
use App\Models\Constants;
use App\Services\Mapper\Facades\Mapper;
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
        $rules = [];

        $rules['investor_name'] = 'required';
        $rules['phone'] = 'required';
        $rules['email'] = 'required|email';
        $rules['category_id'] = 'required';
        $rules['address'] = 'required';
        $rules['donate_id'] = 'required';

        //logistik,medis,etc
        $donateCategoryId = ConstantParser::searchById($this->request->get('donate_id'),
            Constants::DONATION_CATEGORIES);

        if ($donateCategoryId['slug'] === 'logistik') {
            $rules['package_id'] = 'required';
            $rules['quantity'] = 'required|min:1|max:9999999999';
            $rules['files'] = 'required|mimes:docx,doc,pdf,jpeg,jpg,png';
        } else if ($donateCategoryId['slug'] === 'tunai') {
            $rules['bank_id'] = 'required';
            $rules['bank_account'] = 'required';
            $rules['bank_number'] = 'required';
            $rules['amount'] = 'required|digits_between:1,9999999999';
            $rules['files'] = 'required|mimes:jpeg,jpg,png';
        } else {
            $rules['items'] = 'array';
            $rules['items.*.quantity'] = 'required|min:1|max:9999999999';
            $rules['items.*.oum'] = 'required';
            $rules['files'] = 'required|mimes:docx,doc,pdf';
        }

        return $rules;
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
