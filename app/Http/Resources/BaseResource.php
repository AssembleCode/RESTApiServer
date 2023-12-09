<?php

namespace App\Http\Resources;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
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

            if (isset($data['created_by'])) {
                $userRepository = new UserRepository();
                $userInfo = $userRepository->findById($data['created_by']);
                $includesData['created_by_name'] = $userInfo->name ?? null;
            }
            return array_merge($data, $includesData);
        } catch (Exception $exception) {
            return parent::toArray($request);
        }
    }

    // FOR TAKING DATA FROM ANOTHER EXTERNAL API
    public function withApiRelationalData($resource)
    {
        return $resource;
    }
}
