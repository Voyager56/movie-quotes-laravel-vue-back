<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'quote_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
