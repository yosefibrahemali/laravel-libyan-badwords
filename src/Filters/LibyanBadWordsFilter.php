<?php

namespace Yosef\LibyanBadwords\Filters;

class LibyanBadWordsFilter
{
    /**
     * @var array<string>
     */
    protected array $badWords = [];

    public function __construct(array $badWords)
    {
        $this->badWords = $this->normalizeBadWords($badWords);
    }

    /**
     * Check if text contains bad words.
     *
     * @param  string  $text
     * @return bool
     */
    public function contains(string $text): bool
    {
        $words = $this->splitText($text);

        foreach ($words as $word) {
            // الإضافة المهمة: التأكد من أن الكلمة هي سلسلة نصية قبل استدعاء normalize
            if (! is_string($word)) {
                continue; // تجاهل أي عنصر ليس سلسلة نصية لتجنب الخطأ
            }

            $normalized = $this->normalize($word);

            if (in_array($normalized, $this->badWords)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Replaces bad word with the replacement string.
     *
     * @param  string  $text
     * @param  bool  $asterisk
     * @param  string  $replacement
     * @return string
     */
    public function clean(string $text, bool $asterisk = true, string $replacement = '****'): string
    {
        $words = $this->splitText($text);

        foreach ($words as $index => $word) {
            // الإضافة المهمة: التأكد من أن الكلمة هي سلسلة نصية قبل استدعاء normalize
            if (! is_string($word)) {
                continue; // تجاهل أي عنصر ليس سلسلة نصية لتجنب الخطأ
            }

            $normalized = $this->normalize($word);

            if (in_array($normalized, $this->badWords)) {
                $words[$index] = $asterisk ? str_repeat('*', Str::length($words[$index])) : $replacement;
            }
        }

        return implode(' ', $words);
    }

    /**
     * Normalize the text.
     *
     * @param  string  $text
     * @return string
     */
    protected function normalize(string $text): string
    {
        // 1. إزالة الحركات
        $text = preg_replace('/[ّـًٌٍَُِْ]/u', '', $text);

        // 2. توحيد الهمزات والألفات
        $text = str_replace(['أ', 'إ', 'آ', 'ا'], 'ا', $text);
        $text = str_replace('ى', 'ي', $text);
        $text = str_replace('ؤ', 'و', $text);
        $text = str_replace('ئ', 'ي', $text);
        $text = str_replace('ة', 'ه', $text);

        return Str::lower($text);
    }

    /**
     * Split the text to words.
     *
     * @param  string  $text
     * @return array
     */
    protected function splitText(string $text): array
    {
        // تقسيم النص بناءً على المسافات وأي علامات ترقيم متتالية
        // يستخدم PREG_SPLIT_NO_EMPTY لتجنب العناصر الفارغة
        return preg_split('/[\p{P}\s]+|(?<!\w)\s*[\p{P}]+\s*(?!\w)/u', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Normalize the bad words list.
     *
     * @param  array<string>  $badWords
     * @return array<string>
     */
    protected function normalizeBadWords(array $badWords): array
    {
        return array_map(fn (string $word) => $this->normalize($word), $badWords);
    }
}
