<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\payment\PaymentLink;
class PaymentLinkShipped extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
     protected $payment;
    // public function __construct()
    // {
    //     //
    // }
    public function __construct(PaymentLink $payment)
    {
        $this->payment = $payment;
        if ($this->payment->payment_confirm == "paid") {
            $title = ": Payment successfully completed";
        }else{
            $title = '';
        }
        $this->subject("Invoice Number #". $this->payment->invoice_number. '-'. $this->payment->fullname. $title);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('app.email'))->view('emails.payment.paymentlinkShipped')
            ->with([
                'inv_number' => $this->payment->invoice_number,
                'name' => $this->payment->fullname,
                'desc' => $this->payment->desc,
                'amount' => $this->payment->amount,
                'payment_type' => $this->payment->payment_confirm,
            ]);
    }
}
