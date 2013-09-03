<?php

namespace Fresh;

use Fresh\Helper\CrosswordHelper;

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
    public function testAllowedLength(array $words, $lengthIsAllowed)
    {
        $this->assertEquals($lengthIsAllowed, CrosswordHelper::lengthOfWordsIsAllowed($words));
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
    public function testEight(array $words, $isEight)
    {
        $this->assertEquals($isEight, CrosswordHelper::canBeEight($words));
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
    public function testCheckFirstAndLastLettersForCompatibility(array $words, $isCompatible)
    {
        $this->assertEquals($isCompatible, CrosswordHelper::firstAndLastLettersAreCompatible($words));
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

    /**
     * Test words groups
     *
     * @param array $words       Array of words
     * @param array $wordsGroups Array of words groups
     *
     * @dataProvider wordsGroupsProvider
     */
    public function testSplitWordsIntoGroups(array $words, array $wordsGroups)
    {
        $this->assertEquals($wordsGroups, CrosswordHelper::splitWordsIntoGroups($words));
    }

    /**
     * Data provider for testSplitWordsIntoGroups
     *
     * @return array
     */
    public static function wordsGroupsProvider()
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
            [
                'AIRWAY' => [
                    'NEWTON' => [
                        'NOD',
                        'BAA',
                        'YARD',
                        'BURN'
                    ]
                ],
                'NEWTON' => [
                    'AIRWAY' => [
                        'NOD',
                        'BAA',
                        'YARD',
                        'BURN'
                    ]
                ]
            ]
        ];

        $data[1] = [
            [
                'PTC',
                'JYNYFDSGI',
                'ZGPPC',
                'IXEJNDOP',
                'JJFS',
                'SSXXQOFGJUZ'
            ],
            [
                'ZGPPC'       => [
                    'JYNYFDSGI'   => [
                        'PTC',
                        'IXEJNDOP',
                        'JJFS',
                        'SSXXQOFGJUZ'
                    ],
                    'IXEJNDOP'    => [
                        'PTC',
                        'JYNYFDSGI',
                        'JJFS',
                        'SSXXQOFGJUZ'
                    ],
                    'SSXXQOFGJUZ' => [
                        'PTC',
                        'JYNYFDSGI',
                        'IXEJNDOP',
                        'JJFS'
                    ]
                ],
                'JYNYFDSGI'   => [
                    'ZGPPC'       => [
                        'PTC',
                        'IXEJNDOP',
                        'JJFS',
                        'SSXXQOFGJUZ'
                    ],
                    'IXEJNDOP'    => [
                        'PTC',
                        'ZGPPC',
                        'JJFS',
                        'SSXXQOFGJUZ'
                    ],
                    'SSXXQOFGJUZ' => [
                        'PTC',
                        'ZGPPC',
                        'IXEJNDOP',
                        'JJFS'
                    ]
                ],
                'IXEJNDOP'    => [
                    'JYNYFDSGI'   => [
                        'PTC',
                        'ZGPPC',
                        'JJFS',
                        'SSXXQOFGJUZ'
                    ],
                    'ZGPPC'       => [
                        'PTC',
                        'JYNYFDSGI',
                        'JJFS',
                        'SSXXQOFGJUZ'
                    ],
                    'SSXXQOFGJUZ' => [
                        'PTC',
                        'JYNYFDSGI',
                        'ZGPPC',
                        'JJFS'
                    ]
                ],
                'SSXXQOFGJUZ' => [
                    'JYNYFDSGI' => [
                        'PTC',
                        'ZGPPC',
                        'IXEJNDOP',
                        'JJFS'
                    ],
                    'ZGPPC'     => [
                        'PTC',
                        'JYNYFDSGI',
                        'IXEJNDOP',
                        'JJFS'
                    ],
                    'IXEJNDOP'  => [
                        'PTC',
                        'JYNYFDSGI',
                        'ZGPPC',
                        'JJFS'
                    ]
                ],
            ]
        ];

        return $data;
    }

    /**
     * Test first and last letters for compatibility
     *
     * @param int   $lengthX   Length of word X
     * @param int   $lengthY   Length of word Y
     * @param array $fourWords Array of four words
     * @param mixed $expected  Expected
     *
     * @dataProvider compatibilityWithCurrentCrossProvider
     */
    public function testCheckLengthsOfOtherWordsForCompatibilityWithCurrentCross($lengthX, $lengthY, array $fourWords, $expected)
    {
        $this->assertEquals($expected, CrosswordHelper::checkLengthsOfOtherWordsForCompatibilityWithCurrentCross($lengthX, $lengthY, $fourWords));
    }

    /**
     * Data provider for testCheckLengthsOfOtherWordsForCompatibilityWithCurrentCross
     *
     * @return array
     */
    public static function compatibilityWithCurrentCrossProvider()
    {
        $data[0] = [
            5,
            5,
            ['CAT', 'DOG', 'RAT', 'PIG'],
            [
                [
                    'x' => ['CAT', 'DOG'],
                    'y' => ['RAT', 'PIG']
                ],
                [
                    'x' => ['CAT', 'RAT'],
                    'y' => ['DOG', 'PIG']
                ],
                [
                    'x' => ['CAT', 'PIG'],
                    'y' => ['DOG', 'RAT']
                ],
                [
                    'x' => ['DOG', 'RAT'],
                    'y' => ['CAT', 'PIG']
                ],
                [
                    'x' => ['DOG', 'PIG'],
                    'y' => ['CAT', 'RAT']
                ],
                [
                    'x' => ['RAT', 'PIG'],
                    'y' => ['CAT', 'DOG']
                ]
            ]
        ];

        $data[1] = [
            5,
            5,
            ['CAT', 'DOG', 'RAT', 'BIRD'],
            false
        ];

        $data[2] = [
            5,
            5,
            ['CAT', 'DOG', 'PONY', 'BIRD'],
            false
        ];

        $data[3] = [
            5,
            5,
            ['CAT', 'BULL', 'PONY', 'BIRD'],
            false
        ];

        $data[4] = [
            5,
            5,
            ['WOLF', 'BULL', 'PONY', 'BIRD'],
            false
        ];

        $data[5] = [
            5,
            7,
            ['CAT', 'DOG', 'RAT', 'CAMEL'],
            [
                [
                    'x' => ['CAT', 'DOG'],
                    'y' => ['RAT', 'CAMEL']
                ],
                [
                    'x' => ['CAT', 'RAT'],
                    'y' => ['DOG', 'CAMEL']
                ],
                [
                    'x' => ['DOG', 'RAT'],
                    'y' => ['CAT', 'CAMEL']
                ]
            ]
        ];

        $data[6] = [
            7,
            5,
            ['CAT', 'CAMEL', 'DOG', 'RAT'],
            [
                [
                    'x' => ['CAT', 'CAMEL'],
                    'y' => ['DOG', 'RAT']
                ],
                [
                    'x' => ['CAMEL', 'DOG'],
                    'y' => ['CAT', 'RAT']
                ],
                [
                    'x' => ['CAMEL', 'RAT'],
                    'y' => ['CAT', 'DOG']
                ]
            ]
        ];

        return $data;
    }

    /**
     * Test building crossword as string
     *
     * @param array $words        Array of words
     * @param bool  $isCompatible Is compatible
     *
     * @dataProvider crosswordAsStringProvider
     */
    public function testBuildCrosswordAsString($wordX, $wordY, $wordXUp, $wordYUp, $wordXDown, $wordYDown, $crossX, $crossY, $expectedString)
    {
        $this->assertEquals(
            $expectedString,
            CrosswordHelper::buildCrosswordAsString(
                $wordX,
                $wordY,
                $wordXUp,
                $wordYUp,
                $wordXDown,
                $wordYDown,
                $crossX,
                $crossY
            )
        );
    }

    /**
     * Data provider for testCheckFirstAndLastLettersForCompatibility
     *
     * @return array
     */
    public static function crosswordAsStringProvider()
    {
        $data[0] = [
            'AAAAA',
            'AAAAA',
            'AAA',
            'AAA',
            'AAA',
            'AAA',
            2,
            2,
            <<<CROSSWORD
AAA..
A.A..
AAAAA
..A.A
..AAA
CROSSWORD
        ];

        $data[1] = [
            'AAAAAA',
            'AAAAAA',
            'AAAA',
            'AAAA',
            'AAA',
            'AAA',
            3,
            3,
            <<<CROSSWORD
AAAA..
A..A..
A..A..
AAAAAA
...A.A
...AAA
CROSSWORD
        ];

        $data[2] = [
            'NEWTON',
            'AIRWAY',
            'BAA',
            'BURN',
            'YARD',
            'NOD',
            2,
            3,
            <<<CROSSWORD
BAA...
U.I...
R.R...
NEWTON
..A..O
..YARD
CROSSWORD
        ];

        $data[3] = [
            'IXEJNDOP',
            'SSXXQOFGJUZ',
            'JJFS',
            'JYNYFDSGI',
            'ZGPPC',
            'PTC',
            3,
            8,
            <<<CROSSWORD
JJFS....
Y..S....
N..X....
Y..X....
F..Q....
D..O....
S..F....
G..G....
IXEJNDOP
...U...T
...ZGPPC
CROSSWORD
        ];

        return $data;
    }
}
