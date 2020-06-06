<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Libraries\LoginProxy;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiLoginController extends Controller
{
    private $loginProxy;
    private $apiConsumer;

    public function __construct(Application $app, LoginProxy $loginProxy)
    {
        $this->loginProxy = $loginProxy;
        $this->apiConsumer = $app->make('apiconsumer');
    }

    /**
     * Oauth2 Login.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidCredentialsException
     */
    public function login(LoginRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $data = $this->loginProxy->attemptLogin($email, $password);
        try {
            return response()->json($data, $data['header']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Oauth2 Refresh Token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refresh_token');
        return response()->json($this->loginProxy->attemptRefresh($refreshToken));
    }

    /**
     * Oauth2 Logout.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->loginProxy->logout();
        return response()->json(['data' => 'ok'], 200);
    }
}
