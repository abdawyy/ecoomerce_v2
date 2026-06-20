<?php

return [
    'title' => 'Sample Guide — Edit Me',
    'description' => 'A starter PDF template you can customize in Admin → Guides.',
    'html' => <<<'HTML'
<p class="badge">Editable sample template</p>
<p>Use this document as a starting point. Open <strong>Admin → Guides</strong>, edit the HTML fields below, and preview your PDF anytime.</p>

<h2 class="section">What you can change</h2>
<ul>
    <li><strong>Title</strong> — English and Arabic titles at the top of the guide.</li>
    <li><strong>Body HTML</strong> — Headings, paragraphs, lists, and tables (see example below).</li>
    <li><strong>Branding</strong> — Logo, colors, and PDF footer in Admin → Branding.</li>
</ul>

<h2 class="section">Example size chart</h2>
<table class="data">
    <thead>
        <tr>
            <th>Size</th>
            <th>Chest (cm)</th>
            <th>Waist (cm)</th>
            <th>Hips (cm)</th>
        </tr>
    </thead>
    <tbody>
        <tr class="zebra"><td>S</td><td>86–91</td><td>66–71</td><td>91–96</td></tr>
        <tr class="zebra"><td>M</td><td>91–97</td><td>71–76</td><td>96–101</td></tr>
        <tr class="zebra"><td>L</td><td>97–102</td><td>76–81</td><td>101–107</td></tr>
        <tr class="zebra"><td>XL</td><td>102–107</td><td>81–86</td><td>107–112</td></tr>
    </tbody>
</table>

<h2 class="section">Care instructions</h2>
<ol>
    <li>Machine wash cold with similar colors.</li>
    <li>Do not bleach.</li>
    <li>Tumble dry low or hang to dry.</li>
    <li>Iron on low heat if needed.</li>
</ol>

<div class="tip-box">
    <strong>Tip:</strong> Replace this section with your own product care, return policy, or sizing notes. Delete rows from the table or add new columns as needed.
</div>

<p><em>Generated from your store branding. Last updated when you save the guide.</em></p>
HTML,
];
