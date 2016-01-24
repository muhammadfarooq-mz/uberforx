<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Walker extends Eloquent implements UserInterface, RemindableInterface{

	use UserTrait, RemindableTrait, SoftDeletingTrait;

	protected $dates = ['deleted_at'];

    protected $table = 'walker';

}
