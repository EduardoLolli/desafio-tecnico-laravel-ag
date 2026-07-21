<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code'          => $this->code,
            'name'          => $this->name,
            'description'   => $this->description,
            'price'         => (float) $this->price,
            'stock_qtt'     => (int) $this->stock_qtt,
            'barcode'       => $this->barcode,
            'family'        => new ProductFamilyResource($this->whenLoaded('family')),
        ];
    }
}
