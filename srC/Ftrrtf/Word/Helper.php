<?php

namespace Ftrrtf\Word;


/**
 * Class Helper
 * @package Ftrrtf\Word
 */
class Helper
{

    /**
     * Remove word from array by value
     *
     * @param $words
     * @param $word
     */
    public function removeWord($words, $word)
    {
        unset($words[array_search($word, $words)]);
        return $words;
    }

    /**
     * @param $word
     *
     * @return mixed
     */
    public function firstLetter($word)
    {
        return $word[0];
    }

    /**
     * @param $word
     *
     * @return mixed
     */
    public function lastLetter($word)
    {
        return $word[strlen($word) - 1];
    }

}