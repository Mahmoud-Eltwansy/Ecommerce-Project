<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Str;

class ModelPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            // dd($user);
            return true;
        }
    }

    /**
     * Handle dynamic calls into the policy.
     *
     * This method is used to call the permissions methods defined in the policy
     * class. It takes the name of the method and the arguments to be passed to
     * the method, and then checks if the user has the ability to perform the
     * action.
     *
     */
    public function __call($name, $arguments)
    {
        dd($name, $arguments);
        $class_name = Str::replace('Policy', '', class_basename($this));
        $class_name = Str::plural(Str::lower($class_name));

        if ($name == 'viewAny') {
            $name = 'view';
        }
        $ability = $class_name . '.' . Str::kebab($name);

        $user = $arguments[0];

        if (isset($arguments[1])) {
            $model = $arguments[1];
            if ($model->store_id !== $user->store_id) {
                return false;
            }
        }
        return $user->hasAbility($ability);
    }
}
