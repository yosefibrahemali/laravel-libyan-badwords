<?php

namespace Yosef\LibyanBadwords\Filters;

class LibyanBadWordsFilter
{
    protected array $badWords;

    public function __construct()
    {
        $this->badWords = config('libyan_badwords', []);
    }

    /**
     * Normalize text: remove tashkeel, unify letters, remove repeated chars
     */
    protected function normalize(string $text): string
    {
        // إزالة التشكيل
        $text = preg_replace('/[\p{Mn}]/u', '', $text);

        // توحيد بعض الحروف
        $map = [
            'أ' => 'ا',
            'إ' => 'ا',
            'آ' => 'ا',
            'ه' => 'ة',
            'ة' => 'ة',
            'ؤ' => 'و',
            'ى' => 'ي',
            'ئ' => 'ي',
        ];
        $text = strtr($text, $map);

        // إزالة التكرار الزائد
        $text = preg_replace('/(.)\1+/u', '$1', $text);

        // إزالة الفراغات المكررة
        $text = preg_replace('/\s+/', ' ', $text);

        return $text;
    }

    /**
     * Check if text contains bad word
     */
    public function contains(string $text): bool
    {
        $text = $this->normalize($text);

        foreach ($this->badWords as $word) {
            $wordNorm = $this->normalize($word);
            if (mb_stripos($text, $wordNorm) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Replace bad words with stars
     */
    public function clean(string $text): string
    {
        $textNorm = $this->normalize($text);

        foreach ($this->badWords as $word) {
            $wordNorm = $this->normalize($word);
            $pattern = '/' . preg_quote($wordNorm, '/') . '/iu';
            $textNorm = preg_replace($pattern, str_repeat('*', mb_strlen($wordNorm)), $textNorm);
        }

        return $textNorm;
    }
}
