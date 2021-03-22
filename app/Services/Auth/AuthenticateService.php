<?php

namespace App\Services\Auth;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class AuthenticateService
{

    /**
     * Authenticate User.
     * Create the User if user does not exist.
     *
     * @return boolean
     */
    public function authenticateUser(): bool
    {
        try {
            $user = Socialite::driver('github')->user();

            $authUser = $this->findOrCreateUser($user);

            auth()->login($authUser, TRUE);

            return true;
        } catch (\Exception $ex) {
            le('Exception while authenticating user. ', $ex);
            return false;
        }
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $githubUser
     * @return User
     */
    private function findOrCreateUser($githubUser)
    {
        if ($authUser = User::where('provider_id', $githubUser->id)->first()) {
            return $authUser;
        }

        $user = [
            'usertype_id' => UT_ENDUSER,
            'name' => (empty($githubUser->name)) ? $githubUser->user['login']  : $githubUser->name,
            'email' => $githubUser->email,
            'provider_id' => $githubUser->id,
            'provider' => 'GITHUB'
        ];

        return User::create($user);
    }
}
