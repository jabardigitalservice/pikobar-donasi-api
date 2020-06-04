<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Libraries\LoginProxy;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Optimus\ApiConsumer\Facade\ApiConsumer;

/**
 * https://laravel.com/docs/6.x/passport
 */
class AppLoginController extends Controller
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

    public function refresh(Request $request)
    {
        return response()->json($this->loginProxy->attemptRefresh());
    }

    public function logout(Request $request)
    {
        $this->loginProxy->logout();
        return response()->json(['data' => 'ok'], 200);
    }
}
