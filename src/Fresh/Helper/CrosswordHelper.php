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
     * Check if crossword could be made from array of words
     *
     * @param array $words
     *
     * @return bool
     */
    public static function canBeCrossword(array $words)
    {
        $result = false;
        $totalLength = 0;

        foreach ($words as $word) {
            $totalLength += strlen($word);
        }

        // Only even length is allowed for crossword creation
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
     * One item of array looks like this:
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
     * If crossword can be built from input words, then return array with length of top X word and string representation of crossword
     * Otherwise return false
     *
     * @param string $wordX           Word X
     * @param string $wordY           Word Y
     * @param array  $compatibleWords Compatible words
     * @param int    $crossXPosition  Cross X position
     * @param int    $crossYPosition  Cross Y position
     *
     * @return array|bool
     */
    public static function tryToBuildCrossword($wordX, $wordY, array $compatibleWords, $crossXPosition, $crossYPosition)
    {
        list($wordX1, $wordX2) = $compatibleWords['x'];
        list($wordY1, $wordY2) = $compatibleWords['y'];

        // Get first and last letters of each word
        $wordXFirstLetter = $wordX[0];
        $wordXLastLetter  = $wordX[strlen($wordX) - 1];

        $wordYFirstLetter = $wordY[0];
        $wordYLastLetter  = $wordY[strlen($wordY) - 1];

        $wordX1FirstLetter = $wordX1[0];
        $wordX1LastLetter  = $wordX1[strlen($wordX1) - 1];
        $wordX1Length      = strlen($wordX1) - 1;

        $wordX2FirstLetter = $wordX2[0];
        $wordX2LastLetter  = $wordX2[strlen($wordX2) - 1];
        $wordX2Length      = strlen($wordX2) - 1;

        $wordY1FirstLetter = $wordY1[0];
        $wordY1LastLetter  = $wordY1[strlen($wordY1) - 1];
        $wordY1Length      = strlen($wordY1) - 1;

        $wordY2FirstLetter = $wordY2[0];
        $wordY2LastLetter  = $wordY2[strlen($wordY2) - 1];
        $wordY2Length      = strlen($wordY2) - 1;

        // Check each combination of compatible words (first and last letters) for compatibility with cross words
        if (($wordX1FirstLetter == $wordY1FirstLetter)
            && ($wordX1LastLetter == $wordYFirstLetter)
            && ($wordY1LastLetter == $wordXFirstLetter)
            && ($wordXLastLetter == $wordY2FirstLetter)
            && ($wordYLastLetter == $wordX2FirstLetter)
            && ($wordX2LastLetter == $wordY2LastLetter)
            && ($wordX1Length == $crossXPosition) // Also length of the top X word should be same as cross X position
            && ($wordY1Length == $crossYPosition) // similarly for top Y word
        ) {
            return [
                strlen($wordX1) => self::buildCrosswordAsString(
                    $wordX,
                    $wordY,
                    $wordX1,
                    $wordY1,
                    $wordX2,
                    $wordY2,
                    $crossXPosition,
                    $crossYPosition
                )
            ];
        } elseif (($wordX1FirstLetter == $wordY2FirstLetter)
            && ($wordX1LastLetter == $wordYFirstLetter)
            && ($wordY2LastLetter == $wordXFirstLetter)
            && ($wordXLastLetter == $wordY1FirstLetter)
            && ($wordYLastLetter == $wordX2FirstLetter)
            && ($wordX2LastLetter == $wordY1LastLetter)
            && ($wordX1Length == $crossXPosition)
            && ($wordY2Length == $crossYPosition)
        ) {
            return [
                strlen($wordX1) => self::buildCrosswordAsString(
                    $wordX,
                    $wordY,
                    $wordX1,
                    $wordY2,
                    $wordX2,
                    $wordY1,
                    $crossXPosition,
                    $crossYPosition
                )
            ];
        } elseif (($wordX2FirstLetter == $wordY1FirstLetter)
            && ($wordX2LastLetter == $wordYFirstLetter)
            && ($wordY1LastLetter == $wordXFirstLetter)
            && ($wordXLastLetter == $wordY2FirstLetter)
            && ($wordYLastLetter == $wordX1FirstLetter)
            && ($wordX1LastLetter == $wordY2LastLetter)
            && ($wordX2Length == $crossXPosition)
            && ($wordY1Length == $crossYPosition)
        ) {
            return [
                strlen($wordX2) => self::buildCrosswordAsString(
                    $wordX,
                    $wordY,
                    $wordX2,
                    $wordY1,
                    $wordX1,
                    $wordY2,
                    $crossXPosition,
                    $crossYPosition
                )
            ];
        } elseif (($wordX2FirstLetter == $wordY2FirstLetter)
            && ($wordX2LastLetter == $wordYFirstLetter)
            && ($wordY2LastLetter == $wordXFirstLetter)
            && ($wordXLastLetter == $wordY1FirstLetter)
            && ($wordYLastLetter == $wordX1FirstLetter)
            && ($wordX1LastLetter == $wordY1LastLetter)
            && ($wordX2Length == $crossXPosition)
            && ($wordY2Length == $crossYPosition)
        ) {
            return [
                strlen($wordX2) => self::buildCrosswordAsString(
                    $wordX,
                    $wordY,
                    $wordX2,
                    $wordY2,
                    $wordX1,
                    $wordY1,
                    $crossXPosition,
                    $crossYPosition
                )
            ];
        }

        return false;
    }

    /**
     * Build crossword as string
     *
     * @param string $wordXMiddle    Middle X word
     * @param string $wordYMiddle    Middle Y word
     * @param string $wordXTop       Top X word
     * @param string $wordYTop       Top Y word
     * @param string $wordXBottom    Bottom X word
     * @param string $wordYBottom    Bottom Y word
     * @param int    $crossXPosition Cross X position
     * @param int    $crossYPosition Cross Y position
     *
     * @return string Crossword in string format
     */
    public static function buildCrosswordAsString(
        $wordXMiddle,
        $wordYMiddle,
        $wordXTop,
        $wordYTop,
        $wordXBottom,
        $wordYBottom,
        $crossXPosition,
        $crossYPosition)
    {
        $crossword = [];

        // Init reset to ...
        for ($x = 0; $x < strlen($wordXMiddle); $x++) {
            for ($y = 0; $y < strlen($wordYMiddle); $y++) {
                $crossword[$x][$y] = '.';
            }
        }

        // Put letters of words to their places in the crossword matrix
        for ($x = 0; $x < strlen($wordXMiddle); $x++) {
            for ($y = 0; $y < strlen($wordYMiddle); $y++) {
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
                if ($y == $crossYPosition) {
                    if (isset($wordXMiddle[$x])) {
                        $crossword[$x][$y] = $wordXMiddle[$x];
                    }
                }
                // Add middle Y word
                if ($x == $crossXPosition) {
                    if (isset($wordYMiddle[$y])) {
                        $crossword[$x][$y] = $wordYMiddle[$y];
                    }
                }
                // Add bottom X word
                if ((strlen($wordYMiddle) - 1) == $y && $x >= $crossXPosition) {
                    if (isset($wordXBottom[$x - $crossXPosition])) {
                        $crossword[$x][$y] = $wordXBottom[$x - $crossXPosition];
                    }
                }
                // Add bottom Y word
                if (strlen($wordXMiddle) - 1 == $x && $y >= $crossYPosition) {
                    if (isset($wordYBottom[$y - $crossYPosition])) {
                        $crossword[$x][$y] = $wordYBottom[$y - $crossYPosition];
                    }
                }
            }
        }

        // Convert array to string
        $crosswordAsString = '';
        for ($y = 0; $y < count($crossword[0]); $y++) {
            for ($x = 0; $x < count($crossword); $x++) {
                $crosswordAsString .= $crossword[$x][$y];

            }
            if ($y != count($crossword[0]) - 1) {
                $crosswordAsString .= PHP_EOL;
            }
        }

        return $crosswordAsString;
    }
}
