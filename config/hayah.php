<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin notification email
    |--------------------------------------------------------------------------
    */
    'admin_email' => env('HAYAH_ADMIN_EMAIL', env('MAIL_FROM_ADDRESS', 'hello@example.com')),

    /*
    |--------------------------------------------------------------------------
    | Allow new admin registration via /admin/register
    |--------------------------------------------------------------------------
    */
    'allow_admin_register' => env('ALLOW_ADMIN_REGISTER', false),

];
