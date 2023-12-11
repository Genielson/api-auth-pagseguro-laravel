<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Course extends Model {
    protected $table = "course";
    protected $fillable = [
        'name',
        'description',
        'amount_hour',
        'amount_module',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
