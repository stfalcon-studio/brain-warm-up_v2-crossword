<?php

namespace Fre5h;

use Fre5h\Helper\CrosswordHelper;

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
     * @return bool
     */
    public function generate(array $words)
    {
        if (!(CrosswordHelper::lengthOfWordsIsAllowed($words)
            && CrosswordHelper::isEight($words)
            && CrosswordHelper::firstAndLastLettersAreCompatible($words))
        ) {
            return false;
        }

        return false;
    }

}
