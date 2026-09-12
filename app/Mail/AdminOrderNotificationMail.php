<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminOrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /** @var array<string, string> */
    public $pdfPaths;

    /**
     * @param  array<string, string>|string  $pdfPaths  Locale-keyed paths or legacy single path
     */
    public function __construct($order, array|string $pdfPaths)
    {
        $this->order = $order;
        $this->pdfPaths = is_array($pdfPaths)
            ? $pdfPaths
            : ['en' => $pdfPaths];
    }

    public function build()
    {
        $mail = $this->subject('New order #'.$this->order->id)
            ->view('email.admin-order-notification');

        foreach (['en', 'ar'] as $locale) {
            $path = $this->pdfPaths[$locale] ?? null;
            if ($path && is_file($path)) {
                $mail->attach($path, [
                    'as' => 'invoice-'.$locale.'.pdf',
                    'mime' => 'application/pdf',
                ]);
            }
        }

        return $mail;
    }
}
