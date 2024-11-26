<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'name' => $this->resource['name'],
      'brewery_type' => $this->resource['brewery_type'],
      'city' => $this->resource['city'],
      'country' => $this->resource['country']
    ];
  }
}
