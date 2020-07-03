<?php

namespace App\Utilities\Passport;

use Exception;
use GuzzleHttp\Client;
use stdClass;

trait Login
{
    protected function apiLogin($email, $password)
    {
        $http = new Client();

        $responseData = new stdClass();

        try {

            $response = $http->post(config('app.url') . config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $email,
                    'password' => $password,
                ]
            ]);

            $responseData->access_token = collect(json_decode($response->getBody()->getContents(), true))
                ->get('access_token');
            $responseData->success = true;
            $responseData->status = 200;

        } catch (Exception $e) {

            $responseData->access_token = null;
            $responseData->success = false;

            if ($e->getCode() === 400) {

                $responseData->message = 'Invalid request. Please enter a valid email and password.';
                $responseData->status = 400;

            } elseif ($e->getCode() === 401) {

                $responseData->message = 'Your credentials are incorrect. Please try again.';
                $responseData->status = 401;

            } else {

                $responseData->message = 'Something went wrong on the server.';
                $responseData->status = $e->getCode();

            }

            catch_and_return('There was a problem logging in', $e);

        }

        return $responseData;
    }
}
