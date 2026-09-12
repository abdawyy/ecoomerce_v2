<style>
    @page { margin: <?php echo e(config('pdf.page_margin_mm', 15)); ?>mm; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11pt;
        color: #222;
        margin: 0;
        padding: 0;
        direction: <?php echo e($branding['dir']); ?>;
        text-align: <?php echo e($branding['align']); ?>;
    }
    .pdf-header { width: 100%; border-bottom: 2px solid <?php echo e($branding['primaryColor']); ?>; padding-bottom: 12px; margin-bottom: 18px; }
    .pdf-header table { width: 100%; border: none; }
    .pdf-header td { border: none; vertical-align: middle; padding: 0; }
    .pdf-logo { max-width: <?php echo e(config('pdf.logo_max_width', 180)); ?>px; max-height: <?php echo e(config('pdf.logo_max_height', 60)); ?>px; }
    .site-name { font-size: 18pt; font-weight: bold; color: <?php echo e($branding['primaryColor']); ?>; margin: 0; }
    .site-meta { font-size: 9pt; color: #555; line-height: 1.4; }
    .pdf-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        font-size: 8pt;
        color: #666;
        border-top: 1px solid #ddd;
        padding-top: 8px;
        text-align: center;
    }
    h1.doc-title { font-size: 16pt; color: <?php echo e($branding['primaryColor']); ?>; margin: 0 0 8px; }
    h2.section { font-size: 12pt; color: <?php echo e($branding['accentColor']); ?>; margin: 16px 0 8px; border-bottom: 1px solid #eee; padding-bottom: 4px; }
    table.data { width: 100%; border-collapse: collapse; margin: 10px 0; }
    table.data th {
        background: <?php echo e($branding['primaryColor']); ?>;
        color: #fff;
        padding: 8px 6px;
        font-size: 9pt;
        text-align: <?php echo e($branding['align']); ?>;
    }
    table.data td { border: 1px solid #ddd; padding: 7px 6px; font-size: 10pt; }
    table.data tr.zebra:nth-child(even) td { background: #f8f9fa; }
    .card-row { width: 100%; margin-bottom: 14px; }
    .card-row td { width: 50%; vertical-align: top; border: 1px solid #e5e7eb; padding: 10px; background: #fafafa; }
    .badge { display: inline-block; padding: 3px 10px; background: <?php echo e($branding['accentColor']); ?>; color: #fff; font-size: 9pt; border-radius: 4px; }
    .summary { width: 45%; margin-<?php echo e($branding['isRtl'] ? 'right' : 'left'); ?>: auto; margin-top: 12px; }
    .summary td { border: none; padding: 4px 8px; }
    .summary .total td { font-weight: bold; font-size: 12pt; border-top: 2px solid <?php echo e($branding['primaryColor']); ?>; }
    .guide-body { font-size: 11pt; line-height: 1.6; }
    .guide-body h1, .guide-body h2, .guide-body h3 { color: <?php echo e($branding['primaryColor']); ?>; }
    .guide-body .tip-box {
        background: #f0f7ff;
        border-<?php echo e($branding['isRtl'] ? 'right' : 'left'); ?>: 4px solid <?php echo e($branding['primaryColor']); ?>;
        padding: 10px 14px;
        margin: 14px 0;
        font-size: 10pt;
    }
    .guide-body code { background: #f3f4f6; padding: 1px 4px; font-size: 9pt; }
</style>
<?php /**PATH C:\hayah\resources\views/pdf/partials/styles.blade.php ENDPATH**/ ?>