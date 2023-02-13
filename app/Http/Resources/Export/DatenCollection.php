<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DatenCollection extends ResourceCollection
{
    protected array $attributes;

    public function bind(array $value): DatenCollection
    {
        $this->attributes = $value;
        return $this;
    }

    public function toArray($request): array
    {
        return $this->collection->map(callback: fn (DatenResource $resource): array =>
            $resource->attributes(attributes: $this->attributes)->toArray($request)
        )->all();
    }
}
