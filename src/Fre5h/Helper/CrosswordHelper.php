<?php

namespace Fre5h\Helper;

/**
 * Crossword Helper
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class CrosswordHelper
{
    /**
     * Check allowed length for words
     *
     * @param array $words
     *
     * @return bool
     */
    public static function lengthOfWordsIsAllowed(array $words)
    {
        $result = true;

        foreach ($words as $word) {
            $length = strlen($word);
            if ($length < 3 || $length > 30) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    /**
     * Check eight
     *
     * @param array $words
     *
     * @return bool
     */
    public static function isEight(array $words)
    {
        $totalLength = 0;

        foreach ($words as $word) {
            $totalLength += strlen($word);
        }

        return !(bool) ($totalLength % 2);
    }

    /**
     * Check first and last letters for compatibility
     *
     * @param array $words
     *
     * @return bool
     */
    public static function firstAndLastLettersAreCompatible(array $words)
    {
        $result  = true;
        $letters = '';

        foreach ($words as $word) {
            $letters .= substr($word, 0, 1);
            $letters .= substr($word, -1, 1);
        }

        $lettersStats = count_chars($letters, 1);

        foreach ($lettersStats as $letterStats) {
            if ($letterStats % 2 !== 0) {
                $result = false;
                break;
            }
        }

        return $result;
    }
}
