<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'username' => $this->resource->username,
            'email' => $this->resource->email,
            'password' => $this->resource->password,
            'role_id' => $this->resource->role_id,
            'role' => [
                'name' => $this->resource->role->name
            ],
            'avatar' => env("APP_URL")."storage/".$this->resource->avatar,
            'phone' => $this->resource->phone,
            'type_doc' => $this->resource->type_doc,
            'n_doc' => $this->resource->n_doc,
            'birthday' => $this->resource->birthday ? Carbon::parse($this->resource->birthday)->format("d-m-y") : null,
            'designation' => $this->resource->designation
        ];
    }
}
