<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollToken extends Model
{
    protected $fillable = ['poll_id', 'email', 'token', 'expires_at'];
    public $timestamps = true;

    public function poll() {
        return $this->belongsTo(Poll::class);
    }

    public function isExpired() {
        return $this->expires_at < now();
    }
}
