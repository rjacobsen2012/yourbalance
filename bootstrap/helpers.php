<?php

use App\Http\Resources\Api\BudgetSourceResource;
use App\Http\Resources\Api\DescriptionResource;
use App\Http\Resources\Api\PlaceResource;
use App\Http\Resources\Api\SourceNameResource;
use App\Models\Amount;
use App\Models\Budget;
use App\Models\BudgetSource;
use App\Models\BudgetSourceType;
use App\Models\Description;
use App\Models\Label;
use App\Models\Place;
use App\Models\SourceName;
use App\Models\User;
use App\Utilities\Budget\Display\Types\FridayDetail;
use App\Utilities\Budget\Display\Types\FridaySummary;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Inertia\Inertia;

if (! function_exists('component')) {
    /**
     * Similar to view(), but instead of taking the name of a blade template,
     * it takes the name of a registered Vue component. Data passed in will
     * be passed to the component as its props.
     *
     * @param string $name
     * @param array $data
     * @return \Inertia\Response
     */
    function component($name, $data = [])
    {
        return Inertia::render($name, $data);
    }
}

if (! function_exists('authLogout')) {
    /**
     * @return JsonResponse|RedirectResponse
     */
    function authLogout()
    {
        Auth::logout();

        session()->invalidate();

        return loggedOut() ?: redirect()->to('/');
    }
}

if (! function_exists('loggedOut')) {
    function loggedOut()
    {
        return null;
    }
}

if (! function_exists('user')) {
    /**
     * Return an instance of the currently authenticated user model.
     *
     * @return User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    function user()
    {
        return auth()->user();
    }
}

if (! function_exists('catch_and_return')) {
    /**
     * @param $message
     * @param Exception $exception
     * @param bool $showStackTrace
     * @param bool $showTime
     * @return string
     */
    function catch_and_return($message, Exception $exception, $showStackTrace = true, $showTime = true)
    {
        $time = now()->toDateTimeString();
        $message = $showTime ? "{$time}: {$message}" : $message;
        if ($showStackTrace) {
            Log::critical($message . PHP_EOL .
                $exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
        } else {
            Log::critical($message . PHP_EOL . $exception->getMessage());
        }
        return $message;
    }
}

if (! function_exists('getLabel')) {
    /**
     * @param string $name
     * @return Label
     */
    function getLabel(string $name)
    {
        /** @var Label $label */
        $label = Label::where('name', $name)->first();

        if (!$label) {
            $label = Label::create(['name' => $name]);
        }

        return $label;
    }
}
