<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'company' => $this->company,
            'location' => $this->location,
            'logo' => $this->logo,
            'is_highlighted' => $this->is_highlighted,
            'is_active' => $this->is_active,
            'content' => $this->content,
            'apply_link' => $this->apply_link,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
