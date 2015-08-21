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

            // Split words in groups to decrease number of iterations
            $wordsGroups = CrosswordHelper::splitWordsIntoGroups($words);
            // If some groups were found...
            if (!empty($wordsGroups)) {
                // Iterate over each middle X word
                foreach ($wordsGroups as $wordX => $item) {
                    // The iterate over each middle Y word
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
                                    $compatibleWords = CrosswordHelper::checkLengthsOfOtherWordsForCompatibilityWithCurrentCross($lengthX, $lengthY, $otherWords);
                                    if (is_array($compatibleWords)) {
                                        foreach ($compatibleWords as $compatibility) {
                                            // Try to build crossword from current set of words
                                            $crosswordData = CrosswordHelper::tryToBuildCrossword($wordX, $wordY, $compatibility, $i, $j);
                                            if (is_array($crosswordData)) {
                                                $results[key($crosswordData)] = current($crosswordData);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // If some crosswords were found
            if (!empty($results)) {
                // If more the one version of crossword
                if (count($results) > 1) {
                    // The sort by length of top X word
                    ksort($results);
                }

                return array_shift($results); // Get first element from array
            }
        }

        return false;
    }

    /**
     * Preliminary checks that can skip some fake arrays of words
     *
     * @param array $words Words
     *
     * @return bool
     */
    protected function preliminaryChecks(array $words)
    {
        return CrosswordHelper::lengthOfWordsIsAllowed($words)
               && CrosswordHelper::canBeCrossword($words)
               && CrosswordHelper::firstAndLastLettersAreCompatible($words);
    }
}
