<?php

namespace App\Http\Resources\Export;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * The `DatenCollection` class extends the 'ResourceCollection' and is used to format a collection
 *
 * @package App\Http\Resources\Export
 */
class DatenCollection extends ResourceCollection
{
    /**
     * An array of additional attributes to bind to the collection.
     *
     * @var array
     */
    protected array $attributes;

    /**
     * Bind additional attributes to the collection.
     *
     * @param array $value
     * @return DatenCollection
     */
    public function bind(array $value): DatenCollection
    {
        $this->attributes = $value;

        return $this;
    }

    /**
     * Convert the collection to an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this->collection->map(fn (DatenResource $resource): array =>
            $resource->attributes($this->attributes)->toArray($request)
        )->all();
    }
}
