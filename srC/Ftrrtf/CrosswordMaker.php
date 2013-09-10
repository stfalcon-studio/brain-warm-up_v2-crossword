<?php

namespace Ftrrtf;

/**
 * Class CrosswordMaker
 * @package Ftrrtf
 */
class CrosswordMaker
{
    protected $wordsOnPositions = array();

    /**
     * @param $words
     *
     * @return bool
     */
    public function generate($words)
    {
        $result = $this->searchWordPositions($words);

        if ($result) {
            return (new Renderer\Matrix())
                ->render(
                    (new Builder($this->wordsOnPositions))
                        ->build()
                );
        }

        // Не удалось подобрать ни одного варианта
        return false;
    }

    /**
     * Поиск позиций для набора слов
     *
     * @param $words
     *
     * @return bool
     */
    protected function searchWordPositions($words)
    {
        // Сортируем по длине слова, сначала более короткие
        usort(
            $words,
            function ($a, $b) {
               return  strlen($a) > strlen($b);
            }
        );

        $helper = new Word\Helper();

        $count = 0;

        // Начинаем перебирать возможные варианты
        // с верхнего горизонтального слова
        foreach ($words as $topWord) {
            $count++;
            // Удаляем уже используемое слово
            $wordsForLeft = $helper->removeWord($words, $topWord);

            // Подбираем вертикальное левое слово
            foreach ($wordsForLeft as $leftWord) {
                $count++;
                // Проверяем или совпадают первые буквы слов
                $checkLeft = $helper->firstLetter($leftWord) == $helper->firstLetter($topWord);

                // Слово не подошло, пробуем следующее
                if (!$checkLeft) {
                    continue;
                }

                // Слово подошло, подбираем среднее
                $wordsForMiddle = $helper->removeWord($wordsForLeft, $leftWord);
                foreach ($wordsForMiddle as $middleWord) {
                    $count++;
                    // Проверяем или совпадают последняя буква
                    // левого слова с первой среднего и длина слова должна
                    // быть как минимум на два символа больше верхнего
                    $checkMiddle = $helper->lastLetter($leftWord) == $helper->firstLetter($middleWord)
                        && (strlen($middleWord) - strlen($topWord) >= 2);

                    // Слово не подошло, пробуем следующее
                    if (!$checkMiddle) {
                        continue;
                    }

                    // Слово подошло — подбираем вертикальное
                    $wordsForVertical = $helper->removeWord($wordsForMiddle, $middleWord);
                    foreach ($wordsForVertical as $verticalWord) {
                        $count++;
                        // Проверяем последнюю букву верхнего слова с первой буквой вертикального,
                        // и сопадение букв на пересечении вертикального слова со средним
                        // и длина слова должна быть как минимум на два символа больше левого
                        $checkVertical = $helper->lastLetter($topWord) == $helper->firstLetter($verticalWord)
                            && $middleWord[strlen($topWord) - 1] == $verticalWord[strlen($leftWord) - 1]
                            && (strlen($verticalWord) - strlen($leftWord) >= 2);

                        // Слово не подошло, пробуем следующее
                        if (!$checkVertical) {
                            continue;
                        }

                        $wordsForBottom = $helper->removeWord($wordsForVertical, $verticalWord);

                        foreach ($wordsForBottom as $bottomWord) {
                            $count++;
                            // Проверяем последнюю букву вертикального слова с первой буквой нижнего
                            $checkBottom = $helper->lastLetter($verticalWord) == $helper->firstLetter($bottomWord)
                                && strlen($bottomWord) == (strlen($middleWord) - strlen($topWord) + 1);

                            // Слово не подошло, пробуем следующее
                            if (!$checkBottom) {
                                continue;
                            }

                            $wordsForRight = $helper->removeWord($wordsForBottom, $bottomWord);

                            $rightWord = array_pop($wordsForRight);

                            // Проверяем последнюю букву среднего слова с первой буквой правого
                            // и последнюю букву правого с последней нижнего слова а также
                            // проверяем совпадение длины правого с нижней частью вертикального
                            $checkRight = $helper->firstLetter($rightWord) == $helper->lastLetter($middleWord)
                                && $helper->lastLetter($rightWord) == $helper->lastLetter($bottomWord)
                                && strlen($rightWord) == (strlen($verticalWord) - strlen($leftWord) + 1);

                            // Слово не подошло, пробуем следующее
                            if (!$checkRight) {
                                continue;
                            }

                            // Решение найдено, сохраняем найденные позиции
                            $this->wordsOnPositions = array(
                                Word\Position::TOP      => $topWord,
                                Word\Position::LEFT     => $leftWord,
                                Word\Position::MIDDLE   => $middleWord,
                                Word\Position::VERTICAL => $verticalWord,
                                Word\Position::BOTTOM   => $bottomWord,
                                Word\Position::RIGHT    => $rightWord,
                            );

                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
