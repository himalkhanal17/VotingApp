<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'multiple_answers',
        'ends_at',
        'only_unique',
        'active',
    ];
       protected $casts = [
        'ends_at' => 'datetime',
        'active' => 'boolean',
    ];
    public function answerAlternatives()
    {
        return $this->hasMany(AnswerAlternative::class);
    }
     public function getAlternativesAttribute()
    {
        return $this->answerAlternatives;
    }

    // Poll has many votes
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // Poll has many voters through votes
    public function voters()
    {
        return $this->belongsToMany(Voter::class, 'votes')
                    ->withTimestamps();
    }
}
