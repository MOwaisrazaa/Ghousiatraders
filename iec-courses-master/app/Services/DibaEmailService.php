<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class DibaEmailService
{
    protected string $apiUrl;
    protected string $secretToken;
    protected string $fromEmail;
    protected string $replyToEmail;

    public function __construct()
    {
        $this->apiUrl = Config::get('services.custom_email.api_url');
        $this->secretToken = Config::get('services.custom_email.secret_token');
        $this->fromEmail = Config::get('services.custom_email.from_email');
        $this->replyToEmail = Config::get('services.custom_email.reply_to_email');
    }

    /**
     * Send email using Diba API
     *
     * @param string $toEmail Recipient email address
     * @param string $subject Email subject
     * @param string $body HTML email body
     * @return array Response from the email service
     */
    public function sendEmail(string $toEmail, string $subject, string $body): array
    {
        try {
            // Check if required configuration values are present
            if (!$this->apiUrl || !$this->secretToken || !$this->fromEmail || !$this->replyToEmail) {
                Log::error('Email API - Missing required configuration values', [
                    'apiUrl' => $this->apiUrl,
                    'secretToken' => $this->secretToken ? '[exists]' : '[missing]',
                    'fromEmail' => $this->fromEmail,
                    'replyToEmail' => $this->replyToEmail
                ]);
                return [
                    'success' => false,
                    'message' => 'Missing email configuration',
                    'data' => null,
                ];
            }

            // Log the request parameters for debugging
            Log::info('Sending email via Diba API', [
                'to' => $toEmail,
                'subject' => $subject,
                'from' => $this->fromEmail,
                'apiUrl' => $this->apiUrl,
            ]);

            // Prepare payload with correct field names
            $payload = [
                'secret_token' => $this->secretToken,
                'from' => $this->fromEmail,
                'repto' => $this->replyToEmail,
                'to' => $toEmail,
                'subject' => $subject,
                'body' => $body,
                'ctype' => 'html'
            ];

            // Log the request payload before sending
            Log::info('Email API payload:', $payload);

            // Send as form data (not JSON)
            $response = Http::asForm()->post($this->apiUrl, $payload);
            
            // Log the raw response for debugging
            Log::info('Diba email service response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // Try to parse JSON response if possible
            try {
                $result = $response->json();
            } catch (\Exception $e) {
                $result = ['raw_response' => $response->body()];
                Log::warning('Failed to parse JSON response from email service', [
                    'body' => $response->body(),
                    'error' => $e->getMessage()
                ]);
            }
            
            if (!$response->successful()) {
                Log::error('Diba email sending failed', [
                    'response' => $result,
                    'status' => $response->status(),
                    'to' => $toEmail,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to send email - HTTP Status: ' . $response->status(),
                    'data' => $result,
                ];
            }
            
            Log::info('Email sent successfully via Diba API', [
                'to' => $toEmail,
                'subject' => $subject,
            ]);
            
            return [
                'success' => true,
                'message' => 'Email sent successfully',
                'data' => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Exception in Diba email service', [
                'message' => $e->getMessage(),
                'to' => $toEmail,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Send password reset email
     *
     * @param string $toEmail Recipient email
     * @param string $userName User's name
     * @param string $resetLink Password reset link
     * @return array Response from the email service
     */
    public function sendPasswordResetEmail(string $toEmail, string $userName, string $resetLink): array
    {
        $subject = 'Reset Your Password - IEC DawateIslami';
        $body = $this->getPasswordResetEmailTemplate($userName, $resetLink);
        
        return $this->sendEmail($toEmail, $subject, $body);
    }

    /**
     * Get HTML email template for password reset
     */
    private function getPasswordResetEmailTemplate(string $userName, string $resetLink): string
    {
        return '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px;">
                <div style="background-color: #2c3e50; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">
                    <h2 style="margin: 0;">Password Reset Request</h2>
                </div>
                <div style="background-color: white; padding: 30px; border-radius: 0 0 5px 5px;">
                    <p>Hello <strong>' . htmlspecialchars($userName) . '</strong>,</p>
                    
                    <p>You are receiving this email because we received a password reset request for your account.</p>
                    
                    <div style="text-align: center; margin: 20px 0;">
                        <a href="' . htmlspecialchars($resetLink) . '" style="display: inline-block; background-color: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">Reset Password</a>
                    </div>
                    
                    <div style="background-color: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 10px; border-radius: 5px; margin: 15px 0;">
                        <strong>⏱️ Important:</strong> This password reset link will expire in 60 minutes.
                    </div>
                    
                    <p>If you did not request a password reset, no further action is required. Your account will remain secure.</p>
                    
                    <div style="background-color: #d4edda; border: 1px solid #28a745; color: #155724; padding: 10px; border-radius: 5px; margin: 15px 0;">
                        <strong>🔒 Security Notice:</strong> Never share this link with anyone. IEC DawateIslami staff will never ask for your password.
                    </div>
                    
                    <p>If the button above doesn\'t work, copy and paste this link into your browser:</p>
                    <p style="word-break: break-all; background-color: #f5f5f5; padding: 10px; border-radius: 5px;">
                        ' . htmlspecialchars($resetLink) . '
                    </p>
                    
                    <p>Best regards,<br><strong>IEC DawateIslami Team</strong></p>
                </div>
                <div style="background-color: #ecf0f1; padding: 15px; text-align: center; font-size: 12px; color: #7f8c8d; border-radius: 0 0 5px 5px;">
                    <p style="margin: 0;">© 2025 IEC DawateIslami. All Rights Reserved.</p>
                    <p style="margin: 5px 0 0 0;">This is an automated email. Please do not reply to this message.</p>
                </div>
            </div>
        ';
    }
}
