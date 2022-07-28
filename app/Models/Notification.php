<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'to_user_id',
        'from_user_id',
        'type',
        'read',
    ];

    public function to(){
        return $this->belongsTo(User::class, "to_user_id");
    }
    public function from(){
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
