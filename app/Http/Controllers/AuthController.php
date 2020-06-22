<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use Psr\Http\Message\StreamInterface;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|StreamInterface
     */
    public function apiLogin(Request $request)
    {
        $http = new Client();

        try {

            /** @var User $user */
            $user = User::where('email', $request->email)->first();

            $response = $http->post(config('app.url') . config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->email,
                    'password' => $request->password,
                ]
            ]);

            auth()->login($user, true);

            return $response->getBody();

        } catch (BadResponseException $e) {

            if ($e->getCode() === 400) {

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request. Please enter a valid email and password.',
                ], 400);

            } elseif ($e->getCode() === 401) {

                return response()->json([
                    'success' => false,
                    'message' => 'Your credentials are incorrect. Please try again.',
                ], $e->getCode());

            }

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong on the server.',
            ], $e->getCode());

        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->user()->tokens->each(function (Token $token, $key) {
            $token->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ], 200);
    }
}
