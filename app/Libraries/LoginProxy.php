<?php

namespace App\Libraries;

use Illuminate\Foundation\Application;
use App\Exceptions\InvalidCredentialsException;
use App\Repositories\UserRepository;

class LoginProxy
{
    const REFRESH_TOKEN = 'refreshToken';

    private $apiConsumer;

    private $auth;

    private $cookie;

    private $db;

    private $request;

    private $userRepository;

    private $app;

    public function __construct(Application $app, UserRepository $userRepository)
    {
        $this->app = $app;
        $this->userRepository = $userRepository;
        $this->apiConsumer = $app->make('apiconsumer');
        $this->auth = $app->make('auth');
        $this->cookie = $app->make('cookie');
        $this->db = $app->make('db');
        $this->request = $app->make('request');
    }

    /**
     * @param $email
     * @param $password
     * @return array
     * @throws InvalidCredentialsException
     */
    public function attemptLogin($email, $password)
    {
        $user = $this->userRepository->where('email', $email)->first();

        if (!is_null($user)) {
            return $this->proxy('password', [
                'username' => $email,
                'password' => $password
            ]);
        }
        return abort(401);
    }

    /**
     * Attempt to refresh the access token used a refresh token that
     * has been saved in a cookie
     */
    public function attemptRefresh()
    {
        $refreshToken = $this->request->cookie(self::REFRESH_TOKEN);

        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }

    /**
     * @param $grantType
     * @param array $data
     * @return array
     * @throws InvalidCredentialsException
     */
    public function proxy($grantType, array $data = [])
    {
        $data = array_merge($data, [
            'client_id' => env('PASSWORD_CLIENT_ID', '2'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET', '6uK3T8N8afK9xkxOE5zPJVxkwKEkUpi9lidC6pIa'),
            'grant_type' => $grantType
        ]);

        $url = env('OAUTH_CLIENT_URL');

        $response = $this->apiConsumer->post($url, $data);

        if (!$response->isSuccessful()) {
            //return abort(401);
            throw new InvalidCredentialsException();
        }

        $data = json_decode($response->getContent());

        // Create a refresh token cookie
        $this->cookie->queue(
            self::REFRESH_TOKEN,
            $data->refresh_token,
            864000, // 10 days
            null,
            null,
            false,
            true // HttpOnly
        );

        return [
            'token_type' => $data->token_type,
            'access_token' => $data->access_token,
            'refresh_token' => $data->refresh_token,
            'expires_in' => $data->expires_in
        ];
    }

    /**
     * Logs out the user. We revoke access token and refresh token.
     * Also instruct the client to forget the refresh cookie.
     */
    public function logout()
    {
        $accessToken = \Illuminate\Support\Facades\Auth::user()->token();
        \DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);
        $accessToken->revoke();
        $this->cookie->queue($this->cookie->forget(self::REFRESH_TOKEN));
    }
}
