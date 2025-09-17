<?php

namespace Yosef\LibyanBadwords\Filters;

class LibyanBadWordsFilter
{
    protected array $badWords;

    public function __construct()
    {
        // Ensure the words list is an array and filter out any empty strings
        $words = (array) config('libyan_badwords.words', []);
      
        $this->badWords = array_filter($words);
    }

    /**
     * Normalize text for consistent matching
     */
    protected function normalize($text): string
    {
        $text = (string) $text;

        // Remove diacritics
        $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06ED}]/u', '', $text);

        // Unify Arabic letters
        $map = [
            'أ' => 'ا', 'إ' => 'ا', 'آ' => 'ا',
            'ه' => 'ة',
            'ؤ' => 'و', 'ى' => 'ي', 'ئ' => 'ي',
        ];
        $text = strtr($text, $map);

        // Remove redundant spaces
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    /**
     * Replace bad words with a replacement string
     */
    public function clean($text, string $replacement = '****'): string
    {
        $modifiedText = (string) $text;

        // A character set for spaces and diacritics
        $filler = '[\s\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06ED}ـ]*';

        foreach ($this->badWords as $badWord) {
            $wordNorm = $this->normalize($badWord);
            
            // Split the normalized word into an array of its characters
            $chars = preg_split('//u', $wordNorm, -1, PREG_SPLIT_NO_EMPTY);
            
            // Map characters to their possible forms in the original text
            $regexSegments = array_map(function($char) {
                switch ($char) {
                    case 'ا': return '[اأإآ]';
                    case 'و': return '[وؤ]';
                    case 'ي': return '[يئ]';
                    case 'ه': return '[هة]';
                    default: return preg_quote($char);
                }
            }, $chars);

            // Build the regex pattern by joining segments with the filler
            $pattern = '/' . implode($filler, $regexSegments) . '/iu';
            
            // Perform the replacement on the original text
            $modifiedText = preg_replace($pattern, $replacement, $modifiedText);
        }

        return $modifiedText;
    }
}