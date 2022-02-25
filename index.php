<?php

require_once 'Parking.php';

// С помощью данной матрицы возможно задать любое количество этажей и типов транспорта
$capacityFloors = [
    ['c', 't'],     // 1 этаж - допускается парковка транспорта типа 'c' и 't'
    ['c'],          // 2 этаж - допускается парковка транспорта только типа 'c'
    ['c'],          // 3 этаж - допускается парковка транспорта только типа 'c'
];

// Тест
try {
    $inst = new Parking($capacityFloors);

    $availableSpaces = '1 2 3';
    $setOfCars = 'c c c t t c c c';

    echo $inst->getSequence($availableSpaces, $setOfCars);
} catch (Exception $e) {
    echo 'Exception: ', $e->getMessage(), PHP_EOL;
} catch (Error $e) {
    echo "Ошибка: ", $e->getMessage(), PHP_EOL;
}
