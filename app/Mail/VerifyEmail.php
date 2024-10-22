<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Illuminate\Http\Request;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /** 
     * Create a new message instance.
     *
     * @return void 
     */
    protected $user;
    public function __construct(User $user)
    {
        return $this->user = $user;
    } 

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('app.email'))->view('emails.verify.verify_Email')
            ->with(['email'=> $this->user->email, 'first_name'=> $this->user->first_name, 'last_name'=> $this->user->last_name, 'md5'=> $this->user->md5, 'password'=> $this->user->password_text ]);
    }
}
