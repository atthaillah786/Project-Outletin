<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;

class BrandPolicy
{
    /**
     * Determine whether the user can view the brand.
     */
    public function view(?User $user, Brand $brand): bool
    {
        return $user && $user->user_id === $brand->franchisor_id;
    }

    /**
     * Determine whether the user can update the brand.
     */
    public function update(User $user, Brand $brand): bool
    {
        return $user->user_id === $brand->franchisor_id;
    }

    /**
     * Determine whether the user can delete the brand.
     */
    public function delete(User $user, Brand $brand): bool
    {
        return $user->user_id === $brand->franchisor_id;
    }
}
