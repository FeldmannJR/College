<?php

namespace App\Rules;

use App\Enums\UserRole;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class NonRoot implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $id = $value;
        $user = User::find($id);
        if ($user === null) {
            return false;
        }
        $role = $user->user_role;
        if ($role === null) {
            return false;
        }
        if ($role->is(UserRole::Root)) {
            return false;
        }
        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O usúario não pode ser root.';
    }
}
