<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $roleKey = array_search($this->role, Config::get('roles', []));
        $roleTranslation = $roleKey ? __('app.'.$roleKey) : $this->role;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role, // raw role key
            'role_display' => $roleTranslation, // translated role
            'email_verified_at' => $this->email_verified_at ? $this->email_verified_at->toDateTimeString() : null,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            // 'hospital_force' => new HospitalForceResource($this->whenLoaded('hospitalForce')), // Assuming a user might be linked to a hospital force
        ];
    }
}
