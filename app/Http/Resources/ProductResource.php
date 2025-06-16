<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
        'name' => $this->name,
        'price' => $this->price,
        'stock' => $this->stock,
        'description'=> $this->description,
        'category_id' => $this->category_id,
        'category' => [
            'id' => $this->category ? $this->category->id : null,
            'name' => $this->category ? $this->category->name : null,
        ],
        'image_url' => $this->image_url, // use accessor here
        'status' => $this->status,
        ];
    }
    public function getImageAttribute($value) {
    return $value ? url('storage/products/' . $value) : null;
}
}
