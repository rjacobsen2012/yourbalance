<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerInertiaData();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // If you're using Laravel Mix, you can
        // use the mix-manifest.json for this.
        Inertia::version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });
    }

    /**
     * Register data with Inertia so it will be available to pages.
     *
     * @return void
     */
    public function registerInertiaData()
    {
        Inertia::share('app.name', config('app.name'));

        Inertia::share('user', function () {
            $user = user();

            return $user ? $user->load([])->toArray() : [];
        });

        Inertia::share('flash', function () {
            return [
                'message' => session('message'),
                'error' => session('error'),
                'success' => session('success'),
            ];
        });

        Inertia::share('routes', function () {
            return collect(Route::getRoutes())->mapWithKeys(function ($route) {
                return [$route->getName() => $route->uri()];
            });
        });
    }

    protected function gravatar()
    {
        return auth()->user() ? 'https://secure.gravatar.com/avatar/' . md5(auth()->user()->email) . '?size=512' : null;
    }
}
