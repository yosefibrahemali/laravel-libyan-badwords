I have edited and improved your `README.md` file to be more beautiful, professional, and accurate. I've corrected the code examples to be idiomatic for Laravel, updated the configuration to the correct format, and resolved the merge conflict in the license section.

Here is the full, revised content for your `README.md` file.

-----

# 🌐 Laravel Libyan Bad Words Filter

A lightweight **Laravel package** to filter and clean **Libyan offensive words** from text. Supports normalization, diacritics removal, repeated letters, and common spelling variations.

-----

## ⚙️ Installation

Install via Composer:

```bash
composer require yosef/libyan-badwords
```

Publish the configuration file (optional, but highly recommended):

```bash
php artisan vendor:publish --provider="Yosef\LibyanBadwords\LibyanBadWordsServiceProvider" --tag=config
```

This will create `config/libyan_badwords.php`, where you can add your custom list of bad words.

-----

## 🚀 How to Use

### 1\. Simple Text Filtering

Use the service container to resolve the `LibyanBadWordsFilter` instance.

```php
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yosef\LibyanBadwords\LibyanBadWordsFilter;

class MyController extends Controller
{
    public function store(Request $request)
    {
        $text = $request->input('comment');

        // Resolve the filter from the service container
        $filter = app(LibyanBadWordsFilter::class);
        
        // Check if the text contains any bad words
        if ($filter->contains($text)) {
            // If it does, clean the text and get the filtered output
            $cleanText = $filter->clean($text);
            
            // Output: هاذا واحد **** يكتب
            return response()->json(['message' => 'Your text has been filtered.', 'clean_text' => $cleanText]);
        }
        
        // If the text is clean, proceed
        return response()->json(['message' => 'Your text is clean.']);
    }
}
```

### 2\. Customizing the Replacement

You can pass a custom string to the `clean` method to use instead of the default `****`.

```php
$text = "زآمـلل ومبَعّر";

// Use a custom replacement string
$filter = app(LibyanBadwordsFilter::class);
$censoredText = $filter->clean($text, '[censored]');

// Output: [censored] [censored]
```

### 3\. Middleware Example

You can use the package within a middleware to automatically clean all incoming request data.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Yosef\LibyanBadwords\LibyanBadWordsFilter;

class FilterBadWords
{
    public function handle(Request $request, Closure $next)
    {
        // Get the filter instance from the service container
        $filter = app(LibyanBadWordsFilter::class);

        // Filter the 'comment' input field
        if ($filter->contains($request->input('comment'))) {
            $cleanedComment = $filter->clean($request->input('comment'));
            $request->merge(['comment' => $cleanedComment]);
        }

        return $next($request);
    }
}
```

-----

## 📝 Configuration

The `config/libyan_badwords.php` file holds the list of bad words. For optimal performance and accuracy, it's crucial to list words in their **normalized form** (without diacritics, and with unified letters).

```php
<?php

return [
    'words' => [
        'زامل',
        'مبعر',
        'مفشك',
        'قواد',
        'صرم',
        'اكلا',
        'اكله',
        'اكلة',
        'زوامل',
        'بغل',
        'زبر',
        'زبوب',
        'دلزة',
        'دلاوز',
        'دلزاتي',
        'دلزاتك',
        'صرمك',
        'بزي',
        'بز',
        'بزك',
        'طاقتك',
        'طاقتي',
        'طاقتكم',
        'طواقيكم',
        'مباعر',
        'دلزتي',
        'دلاوزي',
        'نبة',
        'ولد نبة',
        'ولد النبة',
        'كسي',
        'طيزي',
        'منيك',
        'زبر طاقتك',
        'زبر امك',
        'زبور',
        'ميبون',
    ],
];
```

Add or remove words from this array as needed.

-----

## 📄 License

MIT License — open-source.