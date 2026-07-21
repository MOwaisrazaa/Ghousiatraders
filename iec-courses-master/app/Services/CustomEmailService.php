<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class CustomEmailService
{
    protected $apiUrl;
    protected $secretToken;
    protected $fromEmail;
    protected $replyToEmail;

    public function __construct()
    {
        $this->apiUrl = Config::get('services.custom_email.api_url');
        $this->secretToken = Config::get('services.custom_email.secret_token');
        $this->fromEmail = Config::get('services.custom_email.from_email');
        $this->replyToEmail = Config::get('services.custom_email.reply_to_email');
    }

    public function sendEmail($to, $subject, $body)
    {
        try {
            $payload = [
                'secret_token' => $this->secretToken,
                'from' => $this->fromEmail,
                'repto' => $this->replyToEmail,
                'to' => $to,
                'subject' => $subject,
                'body' => $body,
                'ctype' => 'html',
            ];

            Log::info('Sending email with parameters:', $payload);

            $response = Http::asForm()->post($this->apiUrl, $payload);

            Log::info('Email API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Exception while sending email', ['exception' => $e]);
            return false;
        }
    }
} 