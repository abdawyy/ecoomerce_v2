<?php

namespace App\Services;

use App\Models\Guide;
use App\Models\orders;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PdfService
{
    public function __construct(protected BrandingService $branding) {}

    public function brandingContext(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $settings = $this->branding->settings();
        $isRtl = $locale === 'ar';
        $logoPath = $this->branding->logoPath();

        return [
            'locale' => $locale,
            'isRtl' => $isRtl,
            'dir' => $isRtl ? 'rtl' : 'ltr',
            'align' => $isRtl ? 'right' : 'left',
            'siteName' => $this->branding->siteName(),
            'tagline' => $locale === 'ar'
                ? ($settings->tagline_ar ?: $this->branding->tagline())
                : ($settings->tagline_en ?: $this->branding->tagline()),
            'supportEmail' => $this->branding->supportEmail(),
            'supportPhone' => $this->branding->supportPhone(),
            'websiteUrl' => url('/'),
            'logoPath' => $logoPath,
            'logoSrc' => $logoPath ? $this->imageDataUri($logoPath) : null,
            'primaryColor' => $this->branding->primaryColor(),
            'accentColor' => $settings->accent_color ?? $this->branding->primaryColor(),
            'footerText' => $locale === 'ar'
                ? ($settings->pdf_footer_ar ?: $settings->pdf_footer_en)
                : ($settings->pdf_footer_en ?: url('/')),
            'thankYou' => $locale === 'ar'
                ? ($settings->pdf_thank_you_ar ?: __('pdf.thank_you_default', [], 'ar'))
                : ($settings->pdf_thank_you_en ?: __('pdf.thank_you_default')),
        ];
    }

    public function invoiceTotals(orders $order): array
    {
        $subtotal = (float) $order->orderItems->sum('price');
        $delivery = (float) ($order->cities->price ?? 0);
        $discountPercent = (float) ($order->discountCodes->discount_percentage ?? 0);
        $discountAmount = round($subtotal * ($discountPercent / 100), 2);
        $grandTotal = (float) $order->total_amount;

        return compact('subtotal', 'delivery', 'discountPercent', 'discountAmount', 'grandTotal');
    }

    public function saveInvoice(orders $order, ?string $locale = null): string
    {
        $locale = $this->normalizeLocale($locale ?? app()->getLocale());
        $dir = storage_path('app/public/invoices');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $path = $dir.'/invoice_'.$order->id.'_'.$locale.'.pdf';
        $this->renderPdf('pdf.invoice', $this->invoiceViewData($order, $locale))->Output($path, Destination::FILE);

        return $path;
    }

    /**
     * @return array{en: string, ar: string}
     */
    public function saveInvoices(orders $order): array
    {
        return [
            'en' => $this->saveInvoice($order, 'en'),
            'ar' => $this->saveInvoice($order, 'ar'),
        ];
    }

    public function streamInvoice(orders $order): Response
    {
        $locale = app()->getLocale();

        return $this->pdfResponse(
            $this->renderPdf('pdf.invoice', $this->invoiceViewData($order, $locale)),
            'invoice_'.$order->id.'.pdf'
        );
    }

    public function streamGuide(Guide $guide, ?string $locale = null): Response
    {
        if ($guide->content_type === 'upload' && $guide->file_path) {
            $full = public_path('storage/'.$guide->file_path);
            if (file_exists($full)) {
                return response()->download($full, $guide->slug.'.pdf');
            }
        }

        $locale = $locale ?? app()->getLocale();
        app()->setLocale($locale);

        return $this->pdfResponse(
            $this->renderPdf('pdf.guide', [
                'guide' => $guide,
                'branding' => $this->brandingContext($locale),
                'title' => $guide->title($locale),
                'bodyHtml' => $guide->htmlContent($locale),
            ], $locale),
            $guide->slug.'.pdf'
        );
    }

    public function previewSampleInvoice(?string $locale = null): Response
    {
        $locale = $this->normalizeLocale($locale);
        app()->setLocale($locale);

        $data = $this->invoiceViewData($this->sampleOrder($locale), $locale, true);

        return $this->pdfResponse(
            $this->renderPdf('pdf.invoice', $data, $locale),
            'sample-invoice-'.$locale.'.pdf'
        );
    }

    public function previewEditableSample(?string $locale = null): Response
    {
        $locale = $this->normalizeLocale($locale);

        return $this->streamHtmlGuide(
            __('pdf_sample.title', [], $locale),
            __('pdf_sample.html', [], $locale),
            $locale,
            'sample-editable-guide.pdf'
        );
    }

    public function streamAdminManual(?string $locale = null): Response
    {
        $locale = $this->normalizeLocale($locale);

        return $this->streamHtmlGuide(
            __('admin_guide.title', [], $locale),
            __('admin_guide.html', [], $locale),
            $locale,
            'admin-website-manual-'.$locale.'.pdf'
        );
    }

    public function streamHtmlGuide(string $title, string $bodyHtml, ?string $locale = null, ?string $filename = null): Response
    {
        $locale = $this->normalizeLocale($locale);
        app()->setLocale($locale);

        $safeName = $filename ?: \Illuminate\Support\Str::slug($title).'.pdf';

        return $this->pdfResponse(
            $this->renderPdf('pdf.guide', [
                'branding' => $this->brandingContext($locale),
                'title' => $title,
                'bodyHtml' => $bodyHtml,
            ], $locale),
            $safeName
        );
    }

    protected function formatInvoiceNotes(?string $notes): ?string
    {
        $notes = trim(strip_tags($notes ?? ''));
        if ($notes === '') {
            return null;
        }

        $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $notes))));

        if ($lines === []) {
            return null;
        }

        $items = array_map(
            fn (string $line) => '<li>'.htmlspecialchars($line, ENT_QUOTES, 'UTF-8').'</li>',
            $lines
        );

        return '<ul>'.implode('', $items).'</ul>';
    }

    protected function normalizeLocale(?string $locale): string
    {
        return in_array($locale, ['en', 'ar'], true) ? $locale : app()->getLocale();
    }

    protected function invoiceViewData(orders $order, ?string $locale = null, bool $samplePreview = false): array
    {
        $locale = $this->normalizeLocale($locale ?? app()->getLocale());

        if ($order->exists) {
            $order->loadMissing([
                'user', 'guestUser', 'discountCodes', 'cities', 'address',
                'orderItems.product.productImages', 'payments',
            ]);
        }

        $branding = $this->brandingContext($locale);
        $settings = $this->branding->settings();
        $rawNotes = $locale === 'ar' ? $settings->invoice_notes_ar : $settings->invoice_notes_en;

        if ($samplePreview && empty(trim($rawNotes ?? ''))) {
            $rawNotes = __('invoice_sample.notes_plain', [], $locale);
        }

        $branding['invoiceNotes'] = $this->formatInvoiceNotes($rawNotes);

        return [
            'order' => $order,
            'branding' => $branding,
            'totals' => $this->invoiceTotals($order),
            'isSamplePreview' => $samplePreview,
        ];
    }

    protected function renderPdf(string $view, array $data, ?string $locale = null): Mpdf
    {
        $locale = $locale ?? app()->getLocale();
        if (! isset($data['branding'])) {
            $data['branding'] = $this->brandingContext($locale);
        }

        $html = view($view, $data)->render();
        $branding = $data['branding'];

        $tempDir = storage_path('app/mpdf');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => config('pdf.page_margin_mm', 15),
            'margin_right' => config('pdf.page_margin_mm', 15),
            'margin_top' => config('pdf.page_margin_mm', 15),
            'margin_bottom' => config('pdf.page_margin_mm', 15) + 5,
            'tempDir' => $tempDir,
            'default_font' => 'dejavusans',
        ]);

        if ($branding['isRtl'] ?? false) {
            $mpdf->SetDirectionality('rtl');
        }

        if (function_exists('ini_set')) {
            @ini_set('pcre.backtrack_limit', '5000000');
        }

        $mpdf->WriteHTML($html);

        return $mpdf;
    }

    protected function pdfResponse(Mpdf $mpdf, string $filename): Response
    {
        return response($mpdf->Output($filename, Destination::STRING_RETURN), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }

    protected function imageDataUri(string $path): string
    {
        $mime = match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'image/jpeg',
        };

        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
    }

    protected function sampleOrder(?string $locale = null): orders
    {
        $locale = $this->normalizeLocale($locale ?? app()->getLocale());
        $t = fn ($key) => __('invoice_sample.'.$key, [], $locale);

        $user = (object) [
            'name' => $t('customer_name'),
            'email' => $t('customer_email'),
        ];

        $address = (object) [
            'address_line1' => $t('address_line1'),
            'address_line2' => $t('address_line2'),
            'phone_number' => $t('phone'),
            'country' => $locale === 'ar' ? 'مصر' : 'Egypt',
        ];

        $city = (object) [
            'name' => $t('city'),
            'price' => 45.00,
        ];

        $discount = (object) [
            'code' => $t('discount_code'),
            'discount_percentage' => 10,
        ];

        $productOne = (object) ['name' => $t('product_1')];
        $productTwo = (object) ['name' => $t('product_2')];

        $items = collect([
            (object) ['quantity' => 2, 'price' => 900.00, 'size' => 'M', 'product' => $productOne],
            (object) ['quantity' => 1, 'price' => 650.00, 'size' => 'L', 'product' => $productTwo],
        ]);

        $subtotal = 1550.00;
        $discountAmount = 155.00;
        $delivery = 45.00;
        $grandTotal = $subtotal - $discountAmount + $delivery;

        $payment = (object) [
            'amount' => $grandTotal,
            'created_at' => now(),
        ];

        $order = new orders([
            'id' => 1024,
            'total_amount' => $grandTotal,
            'status' => 'Completed',
            'created_at' => now()->subHours(2),
        ]);

        $order->setRelation('orderItems', $items);
        $order->setRelation('payments', collect([$payment]));
        $order->setRelation('cities', $city);
        $order->setRelation('discountCodes', $discount);
        $order->setRelation('address', $address);
        $order->setRelation('user', $user);
        $order->setRelation('guestUser', null);

        return $order;
    }

    public function uploadGuideFile($file): string
    {
        $directory = config('pdf.guides_directory', 'guides');
        $targetDir = public_path('storage/'.$directory);
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }
        $fileName = 'guide_'.time().'.'.$file->getClientOriginalExtension();
        $file->move($targetDir, $fileName);

        return $directory.'/'.$fileName;
    }

    public function deleteGuideFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }
        $full = public_path('storage/'.$path);
        if (file_exists($full)) {
            File::delete($full);
        }
    }
}
