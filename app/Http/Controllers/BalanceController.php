<?php

namespace App\Http\Controllers;

use App\Http\Resources\BalanceResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class BalanceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $data = [
            'user' => new UserResource($user)
        ];

        if ($accessToken = $request->header('access_token')) {
            $data['access_token'] = $accessToken;
        }

        return Inertia::render('Balance/Index', $data);
    }
}
