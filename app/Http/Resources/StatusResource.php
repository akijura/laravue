<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'main_status_id' => $this->main_status_id,
            'queue' => $this->queue,
            'status' => $this->status,
            'basic_status' => $this->basic_status,
            'created_at' => $this->created_at,
        ];
    }
}
