<?php

return [
    'title' => 'دليل نموذجي — عدّله كما تشاء',
    'description' => 'قالب PDF جاهز يمكنك تخصيصه من لوحة التحكم → الأدلة.',
    'html' => <<<'HTML'
<p class="badge">قالب نموذجي قابل للتعديل</p>
<p>استخدم هذا المستند كنقطة بداية. افتح <strong>لوحة التحكم → الأدلة</strong>، عدّل حقول HTML، واعرض معاينة PDF في أي وقت.</p>

<h2 class="section">ما يمكنك تغييره</h2>
<ul>
    <li><strong>العنوان</strong> — بالإنجليزية والعربية في أعلى الدليل.</li>
    <li><strong>محتوى HTML</strong> — عناوين، فقرات، قوائم، وجداول (انظر المثال أدناه).</li>
    <li><strong>الهوية</strong> — الشعار والألوان وتذييل PDF من الإعدادات → الهوية.</li>
</ul>

<h2 class="section">مثال جدول المقاسات</h2>
<table class="data">
    <thead>
        <tr>
            <th>المقاس</th>
            <th>الصدر (سم)</th>
            <th>الخصر (سم)</th>
            <th>الورك (سم)</th>
        </tr>
    </thead>
    <tbody>
        <tr class="zebra"><td>S</td><td>86–91</td><td>66–71</td><td>91–96</td></tr>
        <tr class="zebra"><td>M</td><td>91–97</td><td>71–76</td><td>96–101</td></tr>
        <tr class="zebra"><td>L</td><td>97–102</td><td>76–81</td><td>101–107</td></tr>
        <tr class="zebra"><td>XL</td><td>102–107</td><td>81–86</td><td>107–112</td></tr>
    </tbody>
</table>

<h2 class="section">تعليمات العناية</h2>
<ol>
    <li>غسيل آلي بارد مع ألوان متشابهة.</li>
    <li>لا تستخدم المبيض.</li>
    <li>تجفيف منخفض أو تعليق للتجفيف.</li>
    <li>كي بحرارة منخفضة عند الحاجة.</li>
</ol>

<div class="tip-box">
    <strong>نصيحة:</strong> استبدل هذا القسم بتعليمات العناية أو سياسة الإرجاع أو ملاحظات المقاسات الخاصة بك.
</div>

<p><em>يُنشأ من هوية متجرك. يُحدَّث عند حفظ الدليل.</em></p>
HTML,
];
