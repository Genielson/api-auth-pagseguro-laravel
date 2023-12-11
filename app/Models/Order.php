<?php

namespace App\Models;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $table = "order";
    protected $fillable = [
        'course_id',
        'user_id',
        'payment'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function course(){
        return $this->belongsTo(Course::class,'course_id');
    }
}
