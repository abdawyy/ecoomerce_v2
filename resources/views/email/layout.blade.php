@php
    $emailDir = app()->getLocale() === 'ar' ? 'rtl' : 'ltr';
    $emailAlign = $emailDir === 'rtl' ? 'right' : 'left';
    $emailLogo = asset('assets/img/logo-true-label-dark.png');
    $emailSupport = config('hayah.admin_email') ?: 'support@truelabelegypt.com';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $emailDir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'True Label')</title>
</head>
<body style="margin:0;padding:0;background:#f6f1e8;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">@yield('preheader')</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f1e8;margin:0;padding:0;">
        <tr>
            <td align="center" style="padding:28px 12px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:100%;max-width:600px;background:#ffffff;border-collapse:collapse;">
                    <tr>
                        <td align="center" style="background:#1b2740;padding:28px 24px 24px;">
                            <img src="{{ $emailLogo }}" alt="TRUE LABEL" width="168" style="display:block;width:168px;max-width:168px;height:auto;border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td align="{{ $emailAlign }}" dir="{{ $emailDir }}" style="padding:32px 32px 8px;text-align:{{ $emailAlign }};font-family:Tahoma,Arial,sans-serif;color:#1b2740;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:8px 32px 28px;font-family:Tahoma,Arial,sans-serif;font-size:13px;line-height:1.6;color:#6b645b;">
                            {{ __('email.footer') }}<br>
                            <a href="mailto:{{ $emailSupport }}" style="color:#1b2740;text-decoration:none;">{{ $emailSupport }}</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
