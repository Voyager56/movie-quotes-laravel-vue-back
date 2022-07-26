<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_id',
        'type',
        'read',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function from(){
        return $this->belongsTo(User::class, 'from_id');
    }
}
