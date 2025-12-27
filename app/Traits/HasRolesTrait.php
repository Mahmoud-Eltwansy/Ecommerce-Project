<?php

namespace App\Traits;

use App\Models\Role;

trait HasRolesTrait
{
    public function roles()
    {
        return $this->morphToMany(Role::class, 'authorizable', 'role_user');
    }

    /**
     * Checks if the user has the given ability.
     * If the user has the ability set to 'deny' in any of their roles, it returns false.
     * If the user has the ability set to 'allow' in any of their roles and no 'deny', it returns true.
     * If the user has no roles with the given ability, it returns false.
     *
     * @param string $ability
     * @return bool
     */
    public function hasAbility($ability)
    {
        $denied = $this->roles()->whereHas('abilities', function ($query) use ($ability) {
            $query->where('ability', $ability)
                ->where('type', 'deny');
        })->exists();
        if ($denied) {
            return false;
        }

        return $this->roles()->whereHas('abilities', function ($query) use ($ability) {
            $query->where('ability', $ability)
                ->where('type', 'allow');
        })->exists();
    }
}
