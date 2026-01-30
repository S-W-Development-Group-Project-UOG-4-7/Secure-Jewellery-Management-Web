<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'SJM Security Verification');
    }

    public function content(): Content
    {
        return new Content(
            htmlString: '
                <div style="font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4;">
                    <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
                        <h2 style="color: #d4af37;">SJM Access Control</h2>
                        <p>A login attempt requires verification.</p>
                        <p style="font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #333;">' . $this->otp . '</p>
                        <p style="color: #888; font-size: 12px;">This code expires in 5 minutes.</p>
                    </div>
                </div>'
        );
    }
}