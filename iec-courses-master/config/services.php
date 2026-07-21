<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'veevotech' => [
        'key' => env('VEEVOTECH_KEY'),
        'sender' => env('VEEVOTECH_SENDER', 'Default'),
        'header' => env('VEEVOTECH_HEADER', ''),
    ],

    'diba_sms' => [
        'username' => env('DIBA_SMS_USERNAME', 'IEC_Course_test'),
        'secret_key' => env('DIBA_SMS_SECRET_KEY', 'A5bTa8UUFIZUa6Ym0F84sH7RPrxnW0eo3TUZVY8xnoAhDMBU7PtM1NWVAgyU'),
        'api_url' => env('DIBA_SMS_API_URL', 'https://smsg.dibaadm.com/api/broadcast/message'),
    ],

    'youtube' => [
        'api_key' => env('YOUTUBE_API_KEY', 'AIzaSyAO_FJ2SlqU8Q4STEHLGCilw_Y9_11qcW8'),
    ],

    'custom_email' => [
        'api_url' => env('EMAIL_API_URL', 'https://smsg.dibaadm.com/api/send-simple-email'),
        'secret_token' => env('EMAIL_API_SECRET', 'zLSkBg0LGcG38tGywGQOAr5A8FB5Vz2uBBZgiG9HHweMHCaPPDWngNY3DWND'),
        'from_email' => env('EMAIL_API_FROM', 'noreply@dawateislami.net'),
        'reply_to_email' => env('EMAIL_API_REPTO', 'noreply@dawateislami.net'),
        'test_email' => env('EMAIL_TEST_TO', 'talibeabqi@gmail.com'),
    ],

    'production_email' => [
        'api_url' => env('PROD_EMAIL_API_URL', 'https://smsg.dibaadm.com/api/send-simple-email'),
        'secret_token' => env('PROD_EMAIL_API_SECRET', 'qHRFTiw46OoYcR9RBXzbfpMjISd0QmonUY4jiZTOR0CAoCAt4h9TaFD7EvSJ'),
        'from_email' => env('PROD_EMAIL_API_FROM', 'noreply@iecdawateislami.com'),
        'reply_to_email' => env('PROD_EMAIL_API_REPTO', 'support@iecdawateislami.com'),
    ],

    'production_sms' => [
        'username' => env('PROD_DIBA_SMS_USERNAME', 'IEC_Course_live'),
        'secret_key' => env('PROD_DIBA_SMS_SECRET_KEY', 'FwM2fBwz1f788WtGYWng4C3jqMeg7s0AXfDSqsUoY5AP1eTwafZy3zlK8E1V'),
        'api_url' => env('PROD_DIBA_SMS_API_URL', 'https://smsg.dibaadm.com/api/broadcast/message'),
    ],

];
