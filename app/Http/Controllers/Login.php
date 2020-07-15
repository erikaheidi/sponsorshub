<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Minicli\Curly\Client;

class Login extends Controller
{
    public function main(Request $request)
    {
        $login_url = "https://github.com/login/oauth/authorize";
        $client_id = 'f1b7a54be1fbe6530c27';
        $client_secret = '3e8a0a4a1cf55199a8e437564ce5bd844a5a1dee';
        $redirect_uri = 'http://localhost:8000/login';

        $state = $request->query('state');

        if ($state === null) {
            $state = md5(time());
            $auth_url = sprintf('%s?client_id=%s&redirect_uri=%s&state=%s', $login_url, $client_id, $redirect_uri, $state);

            return redirect($auth_url);
        }

        //obtain access token
        //http://localhost:8000/login?code=4bb565abb992cd467330&state=ffa9fc1386d03a342abe7a75b5886f08

        $code = $request->query('code');

        $token_url = 'https://github.com/login/oauth/access_token';

        $curly = new Client();

        //$request_token_url = sprintf('%s', $token_url);

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
            //query for user info
            //saves to DB
        } else {
            print_r($response);
        }

        return "OK";
    }

}
