<?php

namespace Fresh;

use Fresh\Helper\CrosswordHelper;

/**
 * Class CrosswordMaker
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class CrosswordMaker
{
    /**
     * @param array $words
     *
     * @return bool|array
     */
    public function generate(array $words)
    {
        // At first check words by some rules
        if ($this->preliminaryChecks($words)) {
            $results = [];

            $wordsGroups = CrosswordHelper::splitWordsIntoGroups($words);

            if (!empty($wordsGroups)) {
                foreach ($wordsGroups as $wordX => $item) {
                    foreach ($item as $wordY => $otherWords) {
                        $lengthX = strlen($wordX);
                        // Iterate over word X letters, begin from the third letter from start and finish at the third letter from the end
                        for ($i = 2; $i <= $lengthX - 3; $i++) {
                            $lengthY = strlen($wordY);
                            // Iterate over word Y letters, begin from the third letter from start and finish on the third letter from the end
                            for ($j = 2; $j <= $lengthY - 3; $j++) {
                                // Check if word X and word Y have mutual letter, so the cross could be created
                                if ($wordX[$i] == $wordY[$j]) {
                                    // Check lengths of other words for compatibility with current cross
                                    $compatibilities = CrosswordHelper::checkLengthsOfOtherWordsForCompatibilityWithCurrentCross($lengthX, $lengthY, $otherWords);
                                    if (is_array($compatibilities)) {
                                        foreach ($compatibilities as $compatibility) {
                                            // Try to build crossword from current set of words
                                            $crosswordData = CrosswordHelper::tryToBuildCrossword($wordX, $wordY, $compatibility, $i, $j);
                                            if (is_string($crosswordData['string'])) {
                                                $results[$crosswordData['length']] = $crosswordData['string'];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($results)) {

                if (count($results) > 1) {
                    ksort($results);
//                    print_r($results);
                }

                return array_shift($results); // Get first and only one result
            }
        }

        return false;
    }

    /**
     * Preliminary check that can skip some fake arrays of words
     *
     * @param array $words Words
     *
     * @return bool
     */
    protected function preliminaryChecks(array $words)
    {
        return CrosswordHelper::lengthOfWordsIsAllowed($words)
               && CrosswordHelper::canBeEight($words)
               && CrosswordHelper::firstAndLastLettersAreCompatible($words);
    }
}
