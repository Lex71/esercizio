<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BrewCollection extends ResourceCollection
{
  public function toArray($request)
  {
    return [
      'data' => $this->collection
        ->map
        ->toArray($request)
        ->all(),
      'links' => [
        'self' => 'link-value',
      ],
    ];
  }
}
