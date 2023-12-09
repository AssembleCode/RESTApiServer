<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Request;

class ExampleResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        try {
            $data = parent::toArray($request);
            $includesData = [];

            if (isset($data['id'])) {
                $includesData['name'] = 'Rafikul Islam';
            }
            return array_merge($data, $includesData);
        } catch (Exception $exception) {
            return parent::toArray($request);
        }
    }
}
