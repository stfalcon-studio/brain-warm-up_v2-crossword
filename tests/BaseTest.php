<?php

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    protected $crosswordMaker;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->setUpCrossword();
    }

    /**
     * Set up crossword maker
     */
    abstract protected function setUpCrossword();

    /**
     * Test game
     *
     * @param $words
     * @param $expectedCrossword
     *
     * @dataProvider provider
     */
    public function testCrosswordMaker($words, $expectedCrossword)
    {
        $crossword = $this->crosswordMaker->generate($words);
        $this->assertEquals($crossword, $expectedCrossword);
    }

    /**
     * Test data provider
     *
     * @return array
     */
    public static function provider()
    {
        // $data[n] in CLI = CrosswordGenerator::generate() with data set #n

        $data[0] = [
            [
                'NOD',
                'BAA',
                'YARD',
                'AIRWAY',
                'NEWTON',
                'BURN',
            ],
            <<<CROSSWORD
BAA...
U.I...
R.R...
NEWTON
..A..O
..YARD
CROSSWORD
        ];

        $data[1] = [
            [
                'AAA',
                'AAA',
                'AAAAA',
                'AAA',
                'AAA',
                'AAAAA'
            ],
            <<<CROSSWORD
AAA..
A.A..
AAAAA
..A.A
..AAA
CROSSWORD
        ];


        $data[2] = [
            [
                'PTC',
                'JYNYFDSGI',
                'ZGPPC',
                'IXEJNDOP',
                'JJFS',
                'SSXXQOFGJUZ'
            ],
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


        $data[3] = [
            [
                'MPISMEYTWWBYTHA',
                'EJHYPZICDDONIUA',
                'EJOT',
                'YGLLIXXKFPBEPSTKPE',
                'EVBIY',
                'TNKLLGVGTIKQWUYLLXM'
            ],
            <<<CROSSWORD
EJOT..............
V..N..............
B..K..............
I..L..............
YGLLIXXKFPBEPSTKPE
...G.............J
...V.............H
...G.............Y
...T.............P
...I.............Z
...K.............I
...Q.............C
...W..............
CROSSWORD
        ];


        $data[4] = [
            [
                'ABA',
                'CABA',
                'DABA',
                'CABA',
                'GIP',
                'TOII'
            ],
            false
        ];

        $data[5] = [
            [
                'NOD',
                'BAA',
                'YARD',
                'AIRWAY',
                'NEWWON',
                'BURNN'
            ],
            false
        ];

        return $data;
    }

}