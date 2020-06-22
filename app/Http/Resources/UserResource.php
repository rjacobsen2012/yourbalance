<?php

namespace App\Http\Resources;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var User $user */
        $user = $this->resource;

        $response = [];
        $response['first_name'] = $user->first_name;
        $response['last_name'] = $user->last_name;
        $response['name'] = $user->name;
        $response['is_admin'] = $user->is_admin;

        return $response;
    }
}
