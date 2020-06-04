<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use \Laravel\Passport\Http\Controllers\AccessTokenController as ParentAccesstokenController;
use Lcobucci\JWT\Parser;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenController extends ParentAccesstokenController
{
    use ValidatesRequests;

    public function issueToken(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();
        $response =  parent::issueToken($request);
        return $response;
    }

    /**
     * Revoke an access token.
     * @param  Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function revokeToken(Request $request)
    {
        $id = (new Parser())->parse($request->bearerToken())->getHeader('jti');
        $request->user()->tokens->find($id)->revoke();
        return response([], 200);
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param  array $data
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return void
     * @throws ValidationException
     */
    public function validate($data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new ValidationException($validator,
                new JsonResponse($this->formatValidationErrors($validator), 422)
            );
        }
    }
}
