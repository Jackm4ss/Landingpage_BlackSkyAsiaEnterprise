<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'country_code' => $this->registration_country_code,
            'registration_source' => $this->registration_source,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'gender' => $this->gender,
            'is_active' => $this->is_active,
            'roles' => $this->whenLoaded(
                'roles',
                fn () => $this->getRoleNames()->values(),
                fn () => method_exists($this->resource, 'getRoleNames')
                    ? $this->getRoleNames()->values()
                    : [],
            ),
        ];
    }
}
