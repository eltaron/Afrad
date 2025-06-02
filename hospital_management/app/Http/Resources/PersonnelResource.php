<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon; // Added Carbon

class PersonnelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'military_id' => $this->military_id,
            'national_id' => $this->national_id,
            'phone_number' => $this->phone_number,
            'recruitment_date' => $this->recruitment_date ? Carbon::parse($this->recruitment_date)->toDateString() : null,
            'termination_date' => $this->termination_date ? Carbon::parse($this->termination_date)->toDateString() : null,
            'job_title' => $this->job_title,
            'rank' => $this->rank,
            'hospital_force_id' => $this->hospital_force_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'hospital_force' => new HospitalForceResource($this->whenLoaded('hospitalForce')),
            'user' => new UserResource($this->whenLoaded('user')),

            // You might want to include counts or summaries here, or load them conditionally
            // 'leaves_count' => $this->whenLoaded('leaves', fn() => $this->leaves->count()),
            // 'violations_count' => $this->whenLoaded('violations', fn() => $this->violations->count()),
            // 'current_department' => new DepartmentResource($this->whenLoaded('currentDepartment')), // If currentDepartment relation is set up on Personnel model
        ];
    }
}
