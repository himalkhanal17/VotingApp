<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VoteTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $poll;
    public $token;

    public function __construct($poll, $token)
    {
        $this->poll = $poll;
        $this->token = $token;
    }

    public function build()
    {
        $url = route('website.polls.voteForm', ['token' => $this->token]);

        return $this->subject('Your Voting Link')
                    ->view('emails.vote_token')
                    ->with(['url' => $url, 'poll' => $this->poll]);
    }
}
