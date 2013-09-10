<?php

namespace Ftrrtf;


/**
 * Class Builder
 * @package Ftrrtf
 */
class Builder
{
    const HORIZONTAL = 'horizontal';
    const VERTICAL   = 'vertical';

    protected $matrix;
    private $words;

    /**
     * @param $words
     */
    public function __construct($words)
    {
        $width  = strlen($words[Word\Position::MIDDLE]);
        $height = strlen($words[Word\Position::VERTICAL]);

        // Fill matrix
        for ($x = 1; $x <= $height; $x++) {
            for ($y = 1; $y <= $width; $y++) {
                $this->matrix[$x][$y] = false;
            }
        }

        $this->words = $words;
    }


    /**
     * Invoke render
     *
     */
    public function build()
    {
        // Put words to matrix
        $this->putWordToMatrix(
            $this->words[Word\Position::TOP],
            1,
            1,
            self::HORIZONTAL
        );

        $this->putWordToMatrix(
            $this->words[Word\Position::MIDDLE],
            strlen($this->words[Word\Position::LEFT]),
            1,
            self::HORIZONTAL
        );

        $this->putWordToMatrix(
            $this->words[Word\Position::BOTTOM],
            strlen($this->words[Word\Position::VERTICAL]),
            strlen($this->words[Word\Position::TOP]),
            self::HORIZONTAL
        );

        $this->putWordToMatrix(
            $this->words[Word\Position::LEFT],
            1,
            1,
            self::VERTICAL
        );

        $this->putWordToMatrix(
            $this->words[Word\Position::VERTICAL],
            1,
            strlen($this->words[Word\Position::TOP]),
            self::VERTICAL
        );

        $this->putWordToMatrix(
            $this->words[Word\Position::RIGHT],
            strlen($this->words[Word\Position::LEFT]),
            strlen($this->words[Word\Position::MIDDLE]),
            self::VERTICAL
        );

        return $this->matrix;
    }

    /**
     * Put word to matrix
     *
     * @param $word
     * @param $x
     * @param $y
     * @param $direction
     */
    protected function putWordToMatrix($word, $x, $y, $direction)
    {
        $word = str_split($word);
        $wordLength = count($word);

        switch ($direction) {
            case self::HORIZONTAL:
                $end = $wordLength + $y;
                for ($i = $y; $i < $end; $i++) {
                    $this->matrix[$x][$i] = array_shift($word);
                }
                break;
            case self::VERTICAL:
                $end = $wordLength + $x;
                for ($i = $x; $i < $end; $i++) {
                    $this->matrix[$i][$y] = array_shift($word);
                }
                break;
        }
    }
}