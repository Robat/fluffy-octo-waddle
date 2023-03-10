<?php

namespace App\Models\Traits;

trait HasSubscriptions
{
    public function hasTeamSubscription()
    {
        return optional($this->plan)->isForTeams();
    }

    public function doesNotHaveTeamSubscription()
    {
        return !$this->hasTeamSubscription();
    }

    public function hasPiggybackSubscription()
    {
        foreach ($this->teams as $team) {
            if ($team->owner->hasSubscription()) {
                return true;
            }
        }

        return false;
    }

    public function hasSubscription()
    {
        return true;
        // if ($this->hasPiggybackSubscription()) {
        //     return true;
        // }

        // return $this->subscribed('main');
    }

    public function doesNotHaveSubscription()
    {
        return !$this->hasSubscription();
    }

    public function hasCancelled()
    {
        return optional($this->subscription('main'))->cancelled();
    }

    public function hasNotCancelled()
    {
        return !$this->hasCancelled();
    }

    public function isCustomer()
    {
        return $this->hasStripeId();
    }
}
