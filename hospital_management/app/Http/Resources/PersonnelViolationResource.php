<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PersonnelViolationResource extends JsonResource
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
            'personnel_id' => $this->personnel_id,
            'violation_type_id' => $this->violation_type_id,
            'violation_date' => Carbon::parse($this->violation_date)->toDateString(),
            'penalty_type' => $this->penalty_type,
            // Potentially translate penalty_type if needed: __('app.'.$this->penalty_type)
            'penalty_days' => $this->penalty_days,
            'leave_deduction_days' => $this->leave_deduction_days,
            'notes' => $this->notes,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'personnel' => new PersonnelResource($this->whenLoaded('personnel')),
            'violation_type' => new ViolationTypeResource($this->whenLoaded('violationType')),
        ];
    }
}
