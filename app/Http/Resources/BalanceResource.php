<?php

namespace App\Http\Resources;

use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Balance $balance */
        $balance = $this->resource;

        $response = $balance->toArray();

        $response['label'] = new LabelResource($balance->label);

        return $response;
    }
}
