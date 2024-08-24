<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'phone' => $this->phone,
            'description' => $this->description,
        ];
    }
}
