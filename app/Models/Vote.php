<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'answer_alternative_id',
        'voter_id',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function voter()
    {
        return $this->belongsTo(Voter::class);
    }

    public function answerAlternative()
    {
        return $this->belongsTo(AnswerAlternative::class);
    }
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

}

