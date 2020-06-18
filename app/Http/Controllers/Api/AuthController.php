<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * Class AuthController.
 *
 * @package App\Http\Controllers\Api
 */
class AuthController extends ApiController
{
    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }

    public function forgot(ResetPasswordRequest $request)
    {
        $data = $this->sendResetLinkEmail($request);
        return Mapper::object($data->getStatusCode(), 200);
    }
}