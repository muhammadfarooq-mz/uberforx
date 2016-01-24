<?php

class Dog extends Eloquent {

    protected $table = 'dog';

    public function dog()
    {
        return $this->hasOne('Owner', 'dog_id');
    }


}
