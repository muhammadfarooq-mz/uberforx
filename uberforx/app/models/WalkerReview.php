<?php

class WalkerReview extends Eloquent {

    protected $table = 'review_walker';

    public function dog()
    {
        return $this->belongsTo('Dog');
    }

}
