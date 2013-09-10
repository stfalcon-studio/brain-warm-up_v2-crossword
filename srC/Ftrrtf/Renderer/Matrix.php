<?php

namespace Ftrrtf\Renderer;

use Ftrrtf\Word;

/**
 * Class Matrix
 * @package Ftrrtf\Renderer
 */
class Matrix
{
    /**
     * Invoke render
     *
     * @param $matrix
     *
     * @return string
     */
    public function render($matrix)
    {
        $width  = count($matrix[1]);
        $height = count($matrix);

        ob_start();
        for ($x = 1; $x <= $height; $x++) {
            for ($y = 1; $y <= $width; $y++) {
                if (!$matrix[$x][$y]) {
                    print '.';
                }

                print $matrix[$x][$y];
            }
            if ($x < $height) {
                print PHP_EOL;
            }
        }
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}