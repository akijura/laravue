<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'basic_status' => $this->basic_status,
            'begin_date' => $this->begin_date,
            'description' => $this->description,
            'end_date' => $this->end_date,
            'id' => $this->id,
            'main_status_id' => $this->main_status_id,
            'name' => $this->name,
            'status' => $this->status,
        ];
    }
}
