<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCreateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'gender'            => $this->gender,
            'plainPassword'     => $this->plainPassword,
            'birth_date'        => $this->birthDate,
            'address'           => $this->address,
            'phone_number'      => $this->phone_number,
            'deleted_at'        => $this->deleted_at,
            'image'             => $this->image ? asset('storage/' . $this->image) : null,
            'created_at'        => $this->created_at->format('l, d-F-Y H:i'),
            'updated_at'        => $this->updated_at->format('l, d-F-Y H:i'),
            'roles'             => $this->roles,
            'role' => UserRole::from($this->roles->pluck('name')->first())->value,
        ];
    }
}
