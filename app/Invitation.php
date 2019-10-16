<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findByCode($code)
    {
        return static::where('code', $code)->firstOrFail();
    }

    public function hasBeenUsed() 
    {
        return $this->user_id !== null;
    }
}
