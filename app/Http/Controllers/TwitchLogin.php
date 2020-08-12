<?php

namespace App\Http\Controllers;

use App\Credential;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Minicli\Curly\Client;

class TwitchLogin extends Controller
{
    public function main(Request $request)
    {
        $client_id = env('TWITCH_CLIENT_ID');
        $client_secret = env('TWITCH_CLIENT_SECRET');
        $redirect_uri = 'http://localhost:8000/login/twitch';
        $login_url = 'https://id.twitch.tv/oauth2/authorize';

        $state = $request->query('state');

        if ($state === null) {
            $state = md5(time());
            $auth_url = sprintf(
                '%s?response_type=code&client_id=%s&redirect_uri=%s&state=%s&scope=%s',
                $login_url,
                $client_id,
                $redirect_uri,
                $state,
                "channel:read:subscriptions"
            );

            return redirect($auth_url);
        }

        $code = $request->query('code');
        $token_url = 'https://id.twitch.tv/oauth2/token';
        $curly = new Client();

        $response = $curly->post(sprintf(
            '%s?code=%s&client_id=%s&client_secret=%s&grant_type=authorization_code&redirect_uri=%s',
            $token_url,
            $code,
            $client_id,
            $client_secret,
            $redirect_uri
        ), [], ['Accept:', 'application/json']);

        if ($response['code'] == 200) {
            $token_response = json_decode($response['body'], 1);

            $access_token = $token_response['access_token'];

            $user_info = $this->getCurrentUser($curly, $client_id, $access_token);

            if ($user_info) {

                $credential = Credential::firstOrNew([
                    'service_name' => 'twitch',
                    'service_id' => $user_info['user_id'],
                ]);

                if ($credential->user instanceof User) {
                    Auth::login($credential->user);
                    return redirect()->route('index');
                }

                $user = new User();
                $user->login = $user_info['login']  . '@twitch';

                $user->password = md5(time());
                $user->save();

                $credential->service_name = 'twitch';
                $credential->service_id = $user_info['user_id'];
                $credential->service_login = $user_info['login'];
                $credential->access_token = $access_token;

                $user->credentials()->save($credential);

                Auth::login($user);

                return redirect()->route('index');
            }

        } else {
            echo "ERROR.";
            print_r($response);
        }
    }

    public function getCurrentUser(Client $client, $client_id, $access_token)
    {
        $response = $client->get(
            'https://id.twitch.tv/oauth2/validate',
            $this->getHeaders($client_id, $access_token)
        );

        if ($response['code'] == 200) {
            return json_decode($response['body'], 1);
        }

        return null;
    }

    public function getHeaders($client_id, $access_token)
    {
        return [
            "Client-ID: $client_id",
            "Authorization: Bearer $access_token"
        ];
    }
}
