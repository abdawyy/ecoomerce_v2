<?php

return [
    'title' => 'Admin Guide — How to Use the Website',
    'description' => 'Step-by-step manual for store administrators.',
    'html' => <<<'HTML'
<p>This guide explains how to manage your online store from the admin panel. Keep it for your team or print it as PDF.</p>

<h2 class="section">1. Sign in</h2>
<ol>
    <li>Open <strong>/admin/login</strong> in your browser.</li>
    <li>Enter your admin email and password.</li>
    <li>After login you land on the <strong>Dashboard</strong> with live visitors, revenue, and new orders.</li>
</ol>

<h2 class="section">2. Dashboard overview</h2>
<ul>
    <li><strong>Live now</strong> — visitors on the site in the last few minutes.</li>
    <li><strong>Today revenue / orders</strong> — compared to yesterday.</li>
    <li><strong>Pending orders</strong> — orders waiting for processing (toast alert on new order).</li>
    <li><strong>Charts</strong> — last 7 days revenue and traffic.</li>
    <li><strong>Recent orders</strong> — click any order to view details or change status.</li>
</ul>

<h2 class="section">3. Products</h2>
<ol>
    <li>Go to <strong>Products → Add product</strong>.</li>
    <li>Fill name, price, category, type, sizes, colors, and stock.</li>
    <li>Upload product images (first image is the main thumbnail).</li>
    <li>Use <strong>Highest</strong> flag to feature items on the homepage carousel.</li>
    <li>Link a <strong>Size guide</strong> PDF if you created one under Guides.</li>
    <li>Toggle <strong>Active</strong> to show or hide on the storefront.</li>
</ol>

<h2 class="section">4. Categories & types</h2>
<p><strong>Categories</strong> group products (e.g. Tops, Dresses). <strong>Types</strong> add extra filters. Both can be activated or deactivated without deleting data.</p>

<h2 class="section">5. Orders</h2>
<ol>
    <li>Open <strong>Orders</strong> from the sidebar (badge shows pending count).</li>
    <li>Click an order to see items, customer, address, and payment.</li>
    <li>Change status: <em>Pending → Processing → Completed</em> or <em>Cancelled</em>.</li>
    <li>Download the <strong>invoice PDF</strong> from the order page.</li>
    <li>New orders also trigger an email to the admin address in your <code>.env</code> file.</li>
</ol>

<h2 class="section">6. Customers</h2>
<p><strong>Users</strong> lists registered customers. <strong>Guest users</strong> shows checkout guests. You can view profiles and toggle account status.</p>

<h2 class="section">7. Branding & homepage images</h2>
<p>Under <strong>Settings → Branding</strong>:</p>
<ul>
    <li>Upload logo, favicon, and social share image.</li>
    <li>Set site name, tagline, support email and phone.</li>
    <li>Upload <strong>homepage images</strong> — hero banner and two category banners (auto-fit cover).</li>
    <li>Customize PDF footer and thank-you message for invoices.</li>
    <li>Use <strong>Preview sample invoice</strong> to check PDF layout.</li>
</ul>

<h2 class="section">8. SEO settings</h2>
<p><strong>Settings → SEO</strong> controls meta titles, descriptions, Open Graph tags, and per-page SEO. The sitemap is at <strong>/sitemap.xml</strong>.</p>

<h2 class="section">9. Analytics</h2>
<p><strong>Analytics</strong> shows page views, product views, revenue trends, top products, traffic by hour, sales by city, and live visitors. Export CSV for reports.</p>

<h2 class="section">10. Guides & PDFs</h2>
<ol>
    <li>Go to <strong>Guides / PDFs → Add guide</strong>.</li>
    <li>Choose <strong>HTML content</strong> to generate PDF from editable HTML, or upload a ready PDF file.</li>
    <li>Click <strong>Load sample template</strong> to start from the editable sample.</li>
    <li>Preview PDF before publishing. Active guides appear on <strong>/guides</strong> for customers.</li>
    <li>Download this admin manual anytime: English or Arabic PDF buttons on the Guides list page.</li>
</ol>

<h2 class="section">11. Discount codes & cities</h2>
<p>Create promo codes with percentage off and expiry. Manage <strong>Cities</strong> for delivery zones and shipping fees used at checkout.</p>

<h2 class="section">12. Storefront (customer view)</h2>
<ul>
    <li><strong>Home</strong> — hero, featured products, category banners.</li>
    <li><strong>Shop</strong> — browse, filter, add to cart, checkout (cash payment).</li>
    <li><strong>Guides</strong> — download help PDFs you published.</li>
    <li>Language switch: <strong>EN / العربية</strong> in the header.</li>
</ul>

<div class="tip-box">
    <strong>Quick checklist after setup:</strong> Run migrations → set branding & homepage images → add categories & products → test checkout → review analytics → publish guides.
</div>
HTML,
];
