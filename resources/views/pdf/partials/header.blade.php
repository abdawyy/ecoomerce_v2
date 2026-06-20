<div class="pdf-header">
    <table>
        <tr>
            <td style="width: 40%; text-align: {{ $branding['align'] }};">
                @if (!empty($branding['logoSrc'] ?? $branding['logoPath'] ?? null))
                    <img src="{{ $branding['logoSrc'] ?? $branding['logoPath'] }}" class="pdf-logo" alt="{{ $branding['siteName'] }}">
                @else
                    <p class="site-name">{{ $branding['siteName'] }}</p>
                @endif
            </td>
            <td style="text-align: {{ $branding['isRtl'] ? 'left' : 'right' }};">
                <p class="site-name">{{ $branding['siteName'] }}</p>
                <p class="site-meta">{{ $branding['tagline'] }}</p>
                @if ($branding['supportEmail'])
                    <p class="site-meta">{{ $branding['supportEmail'] }}</p>
                @endif
                @if ($branding['supportPhone'])
                    <p class="site-meta">{{ $branding['supportPhone'] }}</p>
                @endif
                <p class="site-meta">{{ $branding['websiteUrl'] }}</p>
            </td>
        </tr>
    </table>
</div>
