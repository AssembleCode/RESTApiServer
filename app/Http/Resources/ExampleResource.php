<?php

namespace App\Http\Resources;

class ExampleResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $data = parent::toArray($request);
        $includesData = [];

        if (isset($data['id'])) {
            $includesData['name'] = 'Rafikul Islam';
        }
        return array_merge($data, $includesData);
    }
}
