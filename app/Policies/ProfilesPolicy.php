<?php

namespace App\Policies;
use App\User;
use App\Profiles;

class ProfilesPolicy extends Policy
{

    /**
     * Determine whether the current user can update the user.
     *
     * @param  \App\User  $currentUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(Profiles $currentUser, Profiles $profile)
    {
        return  empty($profile->id);
    }
    /**
     * Determine whether the current user can update the user.
     *
     * @param  \App\User  $currentUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(Profiles $currentUser, Profiles $profile)
    {
        return $currentUser->id === $profile->user_id;
    }

    /**
     * Determine whether the current user can delete the user.
     *
     * @param  \App\User  $currentUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(Profiles $currentUser, Profiles $profile)
    {
        return false;
    }
}
