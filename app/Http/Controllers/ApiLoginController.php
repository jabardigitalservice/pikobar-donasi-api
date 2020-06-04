<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Libraries\LoginProxy;
use Illuminate\Foundation\Application;
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
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidCredentialsException
     */
    public function login(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            return response()->json($this->loginProxy->attemptLogin($email, $password));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json($this->loginProxy->attemptRefresh());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->loginProxy->logout();
        return response()->json(['data' => 'ok'], 200);
    }
}
