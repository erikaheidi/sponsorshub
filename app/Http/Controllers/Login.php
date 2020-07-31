<?php

namespace App\Http\Controllers;

use App\Credential;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Minicli\Curly\Client;

class Login extends Controller
{
    public function main(Request $request)
    {
        $login_url = "https://github.com/login/oauth/authorize";
        $client_id =  env('GITHUB_CLIENT_ID');
        $client_secret = env('GITHUB_CLIENT_SECRET');
        $redirect_uri = 'http://localhost:8000/login';

        $state = $request->query('state');

        if ($state === null) {
            $state = md5(time());
            $auth_url = sprintf('%s?client_id=%s&redirect_uri=%s&state=%s', $login_url, $client_id, $redirect_uri, $state);

            return redirect($auth_url);
        }
        $code = $request->query('code');
        $token_url = 'https://github.com/login/oauth/access_token';
        $curly = new Client();

        $response = $curly->post(sprintf('%s?code=%s&client_id=%s&client_secret=%s&state=%s&redirect_uri=%s',
            $token_url,
            $code,
            $client_id,
            $client_secret,
            $state,
            $redirect_uri
        ), [], ['Accept:', 'application/json']);

        if ($response['code'] == 200) {
            parse_str($response['body'], $output);
            $access_token = $output['access_token'];

            $user_info_url = 'https://api.github.com/user';
            $response = $curly->get($user_info_url, $this->getHeaders($access_token));

            if ($response['code'] == 200) {
                $user_info = json_decode($response['body'], true);

                $credential = Credential::firstOrNew([
                    'service_name' => 'github',
                    'service_id' => $user_info['id'],
                ]);

                if ($credential->user instanceof User) {
                    Auth::login($credential->user);
                    return redirect()->route('index');
                }

                $user = new User();
                $user->login = $user_info['login'];
                //todo fix
                $user->password = md5(time());
                $user->save();

                $credential->service_name = 'github';
                $credential->service_id = $user_info['id'];
                $credential->service_login = $user_info['login'];
                $credential->access_token = $access_token;

                $user->credentials()->save($credential);

                Auth::login($user);

                return redirect()->route('index');
            }

        } else {
            print_r($response);
        }

        return "OK";
    }

    public function getHeaders($access_token)
    {
        return [
            "User-Agent: SponsorsHub v0.1",
            "Accept: application/vnd.github.v3+json",
            "Authorization: token $access_token"
        ];
    }

}
