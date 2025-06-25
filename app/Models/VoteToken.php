<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class VoteToken extends Model
{
    protected $fillable = ['email', 'poll_id', 'token', 'expires_at'];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function isExpired()
    {
        return now()->greaterThan($this->expires_at);
    }
}