<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'last_voted_at',
    ];

    // A voter can cast many votes
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // A voter participates in many polls through votes
    public function polls()
    {
        return $this->belongsToMany(Poll::class, 'votes')
                    ->withTimestamps();
    }
    
}
