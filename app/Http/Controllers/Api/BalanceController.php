<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BalanceController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $perPage = $request->get('perPage') ?: 10;

        /** @var Collection $balances */
        $balances = $user->balances()->with(['label'])->paginate($perPage);

        $days = $balances->pluck('day')->unique();

        return response()->json([
            'success' => true,
            'balances' => $balances,
            'days' => $days,
            'total_balance' => $user->balances()->sum('amount')
        ], 200);
    }

    /**
     * @param Request $request
     * @param Balance $balance
     * @return JsonResponse
     */
    public function update(Request $request, Balance $balance)
    {
        $label = getLabel($request->get('label'));

        $balance->update([
            'date' => Carbon::parse($request->get('date')),
            'label_id' => $label->id,
            'amount' => $request->get('amount')
        ]);

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $label = getLabel($request->get('label'));

        $user->balances()->create([
            'date' => Carbon::parse($request->get('date')),
            'label_id' => $label->id,
            'amount' => $request->get('amount')
        ]);

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * @param Balance $balance
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Balance $balance)
    {
        $balance->delete();

        return response()->json([
            'success' => true
        ], 200);
    }
}
