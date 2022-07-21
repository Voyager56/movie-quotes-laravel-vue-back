<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class Quote extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['text'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie(){
        return $this->belongsTo(Movie::class);
    }

    public function comments(){
        return $this->hasMany(Comments::class);
    }

}
