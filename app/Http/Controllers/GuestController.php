<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class GuestController extends Controller
{
    /**
     * @return RedirectResponse|Response
     */
    public function index()
    {
        if (auth()->check()) {
            return redirect()->to('/home');
        }

        return component('Dashboard/Guest');
    }
}
