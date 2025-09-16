# 🌐 Laravel Libyan Bad Words Filter

![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)
![Laravel](https://img.shields.io/badge/Laravel-8%2B-red)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)

A lightweight **Laravel package** to filter and clean **Libyan offensive words** from text. Supports normalization, diacritics removal, repeated letters, and common spelling variations.

---

## ⚙️ Installation

Install via Composer:

```bash
composer require yosef/libyan-badwords
```

Publish config (optional):

```bash
php artisan vendor:publish --provider="Yosef\LibyanBadwords\LibyanBadWordsServiceProvider" --tag=config
```

This will create `config/libyan_badwords.php` for custom words.

---

## 🛠 Usage

### Direct Instantiation

```php
use Yosef\LibyanBadwords\Filters\LibyanBadWordsFilter;

$filter = new LibyanBadWordsFilter();

$text = "هاذا واحد زآمـلل يكتب";

if ($filter->contains($text)) {
    echo $filter->clean($text); // Output: هاذا واحد **** يكتب
}
```

### Clean Multiple Words

```php
$text = "زآمـلل ومبَعّر";
echo $filter->clean($text); // Output: **** ****
```

### Custom Replacement

```php
echo str_replace('*', '[censored]', $filter->clean($text));
```

### Middleware Example (Optional)

```php
public function handle($request, Closure $next)
{
    $filter = new \Yosef\LibyanBadwords\Filters\LibyanBadWordsFilter();
    $request->merge(['text' => $filter->clean($request->input('text'))]);
    return $next($request);
}
```

---

## 📝 Config

`config/libyan_badwords.php`:

```php
return [
    'زامل','مبعر','مفشك','قواد','صرم','اكلا','اكله','اكلة',
    'قحبة','شرمولة','شرموطة','زبي','طيز','كس','بز',
    'مكبوب','مطلوق','مكبوس','خنيث','مخنث','مخنب',
    'كلب','حمار','بغل','عرص','عرصية',
    'خنزير','سراق','خولة','حيوان',
    'ابن الشرموطة','ابن القحبة','وجه زبي','مصدي',
    'مدلول','مقرقب','تيس','مغفل','بوش',
    'منيك','طرطور','غبي','مفضوح','موسخ'
];
```

Add or remove words as needed.

---

## 📄 License

MIT License — open-source.
