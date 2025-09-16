# 🌐 Laravel Libyan Bad Words Filter

![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)
![Laravel](https://img.shields.io/badge/Laravel-8%2B-red)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)

A **Laravel package** to filter and clean **Libyan offensive words and slang** from text.  
Supports **normalization**, diacritics removal, repeated letters, and multiple spelling variations.

---

## ✨ Features

- Detect Libyan bad words in **any text input**.
- Clean offensive words with `*` or custom replacement.
- Handles **variations in writing**, diacritics, repeated letters.
- Easy integration in **Laravel projects**.
- Publishable config for custom blocked words list.

---

## ⚙️ Installation

Install via Composer:

```bash
composer require yosef/libyan-badwords
Publish the configuration file:

bash
نسخ الكود
php artisan vendor:publish --provider="Yosef\LibyanBadwords\LibyanBadWordsServiceProvider" --tag=config
This will create config/libyan_badwords.php for custom words.

🛠 Usage
Basic Usage
php
نسخ الكود
use Yosef\LibyanBadwords\Filters\LibyanBadWordsFilter;

// الحصول على singleton من Service Container
$filter = app(LibyanBadWordsFilter::class);

$text = "هاذا واحد زآمـلل يكتب";

// التحقق من وجود كلمات سيئة
if ($filter->contains($text)) {
    echo "تم العثور على كلمات سيئة!";
}

// تنظيف النص
$cleanText = $filter->clean($text);
echo $cleanText; // الناتج: "هاذا واحد **** يكتب"
Clean Multiple Words
php
نسخ الكود
$text = "هاذا واحد زآمـلل ومبَعّر";
echo $filter->clean($text);
// الناتج: "هاذا واحد **** ****"
Customize Replacement
php
نسخ الكود
$text = "هذا مثال على اكله";
$cleanText = str_replace('*', '[censored]', $filter->clean($text));
echo $cleanText;
// الناتج: "هذا مثال على [censored]"
Middleware Example (Optional)
يمكن استخدام الباكج في Middleware لتنظيف أي نصوص تلقائيًا قبل الحفظ أو العرض:

php
نسخ الكود
public function handle($request, Closure $next)
{
    $filter = app(\Yosef\LibyanBadwords\Filters\LibyanBadWordsFilter::class);

    $request->merge([
        'text' => $filter->clean($request->input('text'))
    ]);

    return $next($request);
}
⚙ Configuration
config/libyan_badwords.php يحتوي على قائمة الكلمات السيئة الافتراضية:

php
نسخ الكود
return [
    'زامل','مبعر','مفشك','قواد','صرم','اكلا','اكله','اكلة',
    'قحبة', 'شرمولة', 'شرموطة', 'زبي', 'طيز', 'كس', 'بز',
    'مكبوب', 'مطلوق', 'مكبوس', 'خنيث', 'مخنث', 'مخنب',
    'كلب', 'حمار', 'بغل', 'عرص', 'عرصية',
    'خنزير', 'سراق', 'خولة', 'حيوان',
    'ابن الشرموطة', 'ابن القحبة', 'وجه زبي', 'مصدي',
    'مدلول', 'مقرقب', 'تيس', 'مغفل', 'بوش',
    'منيك', 'طرطور', 'غبي', 'مفضوح', 'موسخ'
];
يمكنك إضافة أو إزالة الكلمات حسب الحاجة.

📄 License
This package is open-sourced software licensed under the MIT license.
