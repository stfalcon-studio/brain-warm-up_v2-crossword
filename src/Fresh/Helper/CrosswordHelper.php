<?php

namespace Fresh\Helper;

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
     * Allowed only >= 3 and =< 30
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
     * Check if eight could be made from array of words
     *
     * @param array $words
     *
     * @return bool
     */
    public static function canBeEight(array $words)
    {
        $result = false;
        $totalLength = 0;

        foreach ($words as $word) {
            $totalLength += strlen($word);
        }

        // Only even length is allowed for eight creation
        if ($totalLength % 2 === 0) {
            $gte5 = 0;

            foreach ($words as $word) {
                if (strlen($word) >= 5) {
                    $gte5++;
                }
            }
            // Should be at least two words with 5 and more letters
            if ($gte5 >= 2) {
                $result = true;
            }
        }

        return $result;
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
            $letters .= substr($word, 0, 1);  // First letter
            $letters .= substr($word, -1, 1); // Second letter
        }

        // Get letters stats
        $lettersStats = count_chars($letters, 1);

        foreach ($lettersStats as $letterStats) {
            // Only even count of same letter is allowed
            if ($letterStats % 2 !== 0) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    /**
     * Split words into few groups for easy processing
     *
     * One item of array should consist of:
     * [longest horizontal word][longest vertical word][
     *     [another word],
     *     [another word],
     *     [another word],
     *     [another word]
     * ]
     * Length of the longest horizontal and vertical words should be >= 5
     *
     * @param array $words Words
     *
     * @return array Words groups
     */
    public static function splitWordsIntoGroups(array $words)
    {
        $result = [];

        foreach ($words as $xKey => $wordX) {
            if (strlen($wordX) >= 5) {
                // Create new array of words without current word
                $otherWords = $words;
                unset($otherWords[$xKey]);

                foreach ($otherWords as $yKey => $wordY) {
                    // Create new array of words without current word
                    $restOfWords = $otherWords;
                    unset($restOfWords[$yKey]);

                    if (strlen($wordY) >= 5) {
                        foreach ($restOfWords as $word) {
                            // Collect other words
                            $result[$wordX][$wordY][] = $word;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Check lengths of other four words for compatibility with current cross
     *
     * @param int   $lengthX   Length of word X
     * @param int   $lengthY   Length of word Y
     * @param array $fourWords Four other words
     *
     * @return array|bool
     */
    public static function checkLengthsOfOtherWordsForCompatibilityWithCurrentCross($lengthX, $lengthY, array $fourWords)
    {
        $result = [];

        // Increase length of X and Y words because summary of lengths of other words will be bigger by 1
        $lengthX++;
        $lengthY++;

        list($word1, $word2, $word3, $word4) = $fourWords;

        $word1Length = strlen($word1);
        $word2Length = strlen($word2);
        $word3Length = strlen($word3);
        $word4Length = strlen($word4);

        if ($word1Length + $word2Length == $lengthX && $word3Length + $word4Length == $lengthY) {
            $result[] = [
                'x' => [$word1, $word2],
                'y' => [$word3, $word4]
            ];
        }
        if ($word1Length + $word3Length == $lengthX && $word2Length + $word4Length == $lengthY) {
            $result[] = [
                'x' => [$word1, $word3],
                'y' => [$word2, $word4]
            ];
        }
        if ($word1Length + $word4Length == $lengthX && $word3Length + $word2Length == $lengthY) {
            $result[] = [
                'x' => [$word1, $word4],
                'y' => [$word2, $word3]
            ];
        }
        if ($word2Length + $word3Length == $lengthX && $word1Length + $word4Length == $lengthY) {
            $result[] = [
                'x' => [$word2, $word3],
                'y' => [$word1, $word4]
            ];
        }
        if ($word2Length + $word4Length == $lengthX && $word1Length + $word3Length == $lengthY) {
            $result[] = [
                'x' => [$word2, $word4],
                'y' => [$word1, $word3]
            ];
        }
        if ($word3Length + $word4Length == $lengthX && $word1Length + $word2Length == $lengthY) {
            $result[] = [
                'x' => [$word3, $word4],
                'y' => [$word1, $word2]
            ];
        }

        // If some compatibility was found then return this array
        if (!empty($result)) {
            return $result;
        }
        // Otherwise return false
        return false;
    }

    /**
     * Try to build crossword
     *
     * @param string $wordX         Word X
     * @param string $wordY         Word Y
     * @param array  $compatibility Compatibility
     * @param int    $crossX        Cross at position
     * @param int    $crossY        Cross at position
     *
     * @return array|bool
     */
    public static function tryToBuildCrossword($wordX, $wordY, array $compatibility, $crossX, $crossY)
    {
        list($wordX1, $wordX2) = $compatibility['x'];
        list($wordY1, $wordY2) = $compatibility['y'];

        $wordXFirstLetter = $wordX[0];
        $wordXLastLetter  = $wordX[strlen($wordX) - 1];

        $wordYFirstLetter = $wordY[0];
        $wordYLastLetter  = $wordY[strlen($wordY) - 1];

        $wordX1FirstLetter = $wordX1[0];
        $wordX1LastLetter  = $wordX1[strlen($wordX1) - 1];
        $wordX2FirstLetter = $wordX2[0];
        $wordX2LastLetter  = $wordX2[strlen($wordX2) - 1];

        $wordY1FirstLetter = $wordY1[0];
        $wordY1LastLetter  = $wordY1[strlen($wordY1) - 1];
        $wordY2FirstLetter = $wordY2[0];
        $wordY2LastLetter  = $wordY2[strlen($wordY2) - 1];

        if (($wordX1FirstLetter == $wordY1FirstLetter)
            && ($wordX1LastLetter == $wordYFirstLetter)
            && ($wordY1LastLetter == $wordXFirstLetter)
            && ($wordXLastLetter == $wordY2FirstLetter)
            && ($wordYLastLetter == $wordX2FirstLetter)
            && ($wordX2LastLetter == $wordY2LastLetter)
            && (strlen($wordX1) == $crossX + 1)
            && (strlen($wordY1) == $crossY + 1)
        ) {
            return [
                'length' => strlen($wordX1),
                'string' => self::buildCrosswordAsString($wordX, $wordY, $wordX1, $wordY1, $wordX2, $wordY2, $crossX, $crossY)
            ];
        } elseif (($wordX1FirstLetter == $wordY2FirstLetter)
            && ($wordX1LastLetter == $wordYFirstLetter)
            && ($wordY2LastLetter == $wordXFirstLetter)
            && ($wordXLastLetter == $wordY1FirstLetter)
            && ($wordYLastLetter == $wordX2FirstLetter)
            && ($wordX2LastLetter == $wordY1LastLetter)
            && (strlen($wordX1) == $crossX + 1)
            && (strlen($wordY2) == $crossY + 1)
        ) {
            return [
                'length' => strlen($wordX1),
                'string' => self::buildCrosswordAsString($wordX, $wordY, $wordX1, $wordY2, $wordX2, $wordY1, $crossX, $crossY)
            ];
        } elseif (($wordX2FirstLetter == $wordY1FirstLetter)
            && ($wordX2LastLetter == $wordYFirstLetter)
            && ($wordY1LastLetter == $wordXFirstLetter)
            && ($wordXLastLetter == $wordY2FirstLetter)
            && ($wordYLastLetter == $wordX1FirstLetter)
            && ($wordX1LastLetter == $wordY2LastLetter)
            && (strlen($wordX2) == $crossX + 1)
            && (strlen($wordY1) == $crossY + 1)
        ) {
            return [
                'length' => strlen($wordX2),
                'string' => self::buildCrosswordAsString($wordX, $wordY, $wordX2, $wordY1, $wordX1, $wordY2, $crossX, $crossY)
            ];
        } elseif (($wordX2FirstLetter == $wordY2FirstLetter)
            && ($wordX2LastLetter == $wordYFirstLetter)
            && ($wordY2LastLetter == $wordXFirstLetter)
            && ($wordXLastLetter == $wordY1FirstLetter)
            && ($wordYLastLetter == $wordX1FirstLetter)
            && ($wordX1LastLetter == $wordY1LastLetter)
            && (strlen($wordX2) == $crossX + 1)
            && (strlen($wordY2) == $crossY + 1)
        ) {
            return [
                'length' => strlen($wordX2),
                'string' => self::buildCrosswordAsString($wordX, $wordY, $wordX2, $wordY2, $wordX1, $wordY1, $crossX, $crossY)
            ];
        }

        return false;
    }

    /**
     * Build crossword in string format
     *
     * @param string $wordX       Middle X word
     * @param string $wordY       Middle Y word
     * @param string $wordXTop    Top X word
     * @param string $wordYTop    Top Y word
     * @param string $wordXBottom Bottom X word
     * @param string $wordYBottom Bottom Y word
     * @param int    $crossX      Cross X position
     * @param int    $crossY      Cross Y position
     *
     * @return string Crossword in string format
     */
    public static function buildCrosswordAsString($wordX, $wordY, $wordXTop, $wordYTop, $wordXBottom, $wordYBottom, $crossX, $crossY)
    {
        $crosswordAsString = '';
        $crossword = [];

        // Init reset
        for ($x = 0; $x <= strlen($wordX) - 1; $x++) {
            for ($y = 0; $y <= strlen($wordY) - 1; $y++) {
                $crossword[$x][$y] = '.';
            }
        }

        for ($x = 0; $x <= strlen($wordX) - 1; $x++) {
            for ($y = 0; $y <= strlen($wordY) - 1; $y++) {
                // Add top X word
                if (0 == $y) {
                    if (isset($wordXTop[$x])) {
                        $crossword[$x][$y] = $wordXTop[$x];
                    }
                }
                // Add top Y word
                if (0 == $x) {
                    if (isset($wordYTop[$y])) {
                        $crossword[$x][$y] = $wordYTop[$y];
                    }
                }
                // Add middle X word
                if ($y == $crossY) {
                    if (isset($wordX[$x])) {
                        $crossword[$x][$y] = $wordX[$x];
                    }
                }
                // Add middle Y word
                if ($x == $crossX) {
                    if (isset($wordY[$y])) {
                        $crossword[$x][$y] = $wordY[$y];
                    }
                }
                // Add bottom X word
                if ((strlen($wordY) - 1) == $y && $x >= $crossX) {
                    if (isset($wordXBottom[$x - $crossX])) {
                        $crossword[$x][$y] = $wordXBottom[$x - $crossX];
                    }
                }
                // Add bottom Y word
                if (strlen($wordX) - 1 == $x && $y >= $crossY) {
                    if (isset($wordYBottom[$y - $crossY])) {
                        $crossword[$x][$y] = $wordYBottom[$y - $crossY];
                    }
                }
            }
        }

        for ($y = 0; $y <= count($crossword[0]) - 1; $y++) {
            for ($x = 0; $x <= count($crossword) - 1; $x++) {
                $crosswordAsString .= $crossword[$x][$y];

            }
            if ($y != count($crossword[0]) - 1) {
                $crosswordAsString .= PHP_EOL;
            }
        }

        return $crosswordAsString;
    }
}
