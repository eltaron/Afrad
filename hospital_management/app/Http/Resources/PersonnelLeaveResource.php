<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PersonnelLeaveResource extends JsonResource
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
            'leave_type_id' => $this->leave_type_id,
            'start_date' => Carbon::parse($this->start_date)->toDateString(),
            'end_date' => Carbon::parse($this->end_date)->toDateString(),
            'days_taken' => $this->days_taken,
            'status' => $this->status,
            'status_display' => $this->status ? __("app.{$this->status}", [], 'ar') : null, // Translated status
            'approved_by' => $this->approved_by,
            'notes' => $this->notes,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'personnel' => new PersonnelResource($this->whenLoaded('personnel')),
            'leave_type' => new LeaveTypeResource($this->whenLoaded('leaveType')),
            'approver' => new UserResource($this->whenLoaded('approver')),
        ];
    }
}
