<?php

namespace App\Traits;

use App\Models\Vote;

trait Votable
{

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voted');
    }

    public function vote($user_id)
    {
        $attribs = ["user_id" => $user_id];

        if (! $this->votes()->where($attribs)->exists()) {
            return $this->votes()->create($attribs);
        }

        $this->votes()->where($attribs)->delete();

        return $this->votes()->create($attribs);

    }

}
