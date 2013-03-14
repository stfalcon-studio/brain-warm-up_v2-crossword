<?php

namespace Fre5h;

use Fre5h\Helper\CrosswordHelper;

/**
 * Class CrosswordHelperTest
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class CrosswordHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test allowed length
     *
     * @param array $words           Array of words
     * @param bool  $lengthIsAllowed Length is allowed
     *
     * @dataProvider allowedLengthProvider
     */
    public function testAllowedLength($words, $lengthIsAllowed)
    {
        $result = CrosswordHelper::lengthOfWordsIsAllowed($words);
        $this->assertEquals($result, $lengthIsAllowed);
    }

    /**
     * Data provider for testAllowedLength
     *
     * @return array
     */
    public static function allowedLengthProvider()
    {
        $data[0] = [
            [
                'NOD',
                'BAA',
                'YARD',
                'AIRWAY',
                'NEWTON',
                'BURN',
            ],
            true
        ];

        $data[1] = [
            [
                'NO',
                'BAA',
                'YARD',
                'AIRWAY',
                'NEWTON',
                'BURN',
            ],
            false
        ];

        $data[2] = [
            [
                'NOD',
                'BAA',
                'YARD',
                'AIRWAY',
                'NEWTONNEWTONNEWTONNEWTONNEWTONX',
                'BURN',
            ],
            false
        ];

        return $data;
    }

    /**
     * Test eight
     *
     * @param array $words   Array of words
     * @param bool  $isEight Is eight
     *
     * @dataProvider eightProvider
     */
    public function testEight($words, $isEight)
    {
        $result = CrosswordHelper::isEight($words);
        $this->assertEquals($result, $isEight);
    }

    /**
     * Data provider for testEight
     *
     * @return array
     */
    public static function eightProvider()
    {
        $data[0] = [
            [
                'NOD',
                'BAA',
                'YARD',
                'AIRWAY',
                'NEWTON',
                'BURN',
            ],
            true
        ];

        $data[1] = [
            [
                'NODD',
                'BAA',
                'YARD',
                'AIRWAY',
                'NEWTON',
                'BURN',
            ],
            false
        ];

        return $data;
    }

    /**
     * Test first and last letters for compatibility
     *
     * @param array $words        Array of words
     * @param bool  $isCompatible Is compatible
     *
     * @dataProvider compatibleLettersProvider
     */
    public function testCheckFirstAndLastLettersForCompatibility($words, $isCompatible)
    {
        $result = CrosswordHelper::firstAndLastLettersAreCompatible($words);
        $this->assertEquals($result, $isCompatible);
    }

    /**
     * Data provider for testCheckFirstAndLastLettersForCompatibility
     *
     * @return array
     */
    public static function compatibleLettersProvider()
    {
        $data[0] = [
            [
                'NOD',
                'BAA',
                'YARD',
                'AIRWAY',
                'NEWTON',
                'BURN',
            ],
            true
        ];

        $data[1] = [
            [
                'NOT',
                'BAA',
                'YARD',
                'AIRWAY',
                'NEWTON',
                'BURN',
            ],
            false
        ];

        return $data;
    }
}
