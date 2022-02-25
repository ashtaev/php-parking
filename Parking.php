<?php

/**
 * Класс определяет возможность занять парковочное пространство разными типами транспорта
 */

class Parking
{
    /** @var array $capacityFloors Матрица - количество этажей и типов транспорта */
    private $capacityFloors;

    /** @var array $uniqueTypeCars Набор типов транспортных средств */
    private $uniqueTypeCars;

    /**
     * @param array $capacityFloors
     * @throws Exception
     */
    public function __construct(array $capacityFloors = [])
    {
        if (empty($capacityFloors)) {
            throw new Exception ('Необходимо указать какие типы автомобилей на каких этажах могут парковаться');
        }

        foreach ($capacityFloors as $floor) {
            if (!is_array($floor) || empty($floor)) {
                throw new Exception ('Формат данных об этаже должен быть представлен массивом');
            }

            foreach ($floor as $typeOfCar) {
                if (!is_string($typeOfCar)) {
                    throw new Exception ('Формат данных о типе транспорта должен быть представлен строкой');
                }
            }
        }

        $this->capacityFloors = $capacityFloors;
        $this->uniqueTypeCars = $this->getUniqueTypeCars();
    }

    /**
     * @param string $initAvailableSpaces Параметр количества доступных мест - строку из чисел, разделенных пробелами
     * @param string $setOfCars Неопределенный набор въезжающих на парковку автомобилей - набор символов, разделенных пробелами
     * @return string Последовательность, где для каждого автомобиля вернется признак удачного (y) или неудачного (n) занятия парковочного места
     * @throws Exception
     */
    public function getSequence(string $initAvailableSpaces, string $setOfCars): string
    {

        if (!preg_match('/^[0-9 ]+$/', $initAvailableSpaces)) {
            throw new Exception ('Параметр количества доступных мест на этаже должен представлять строку
                                  из натуральных чисел, разделенных пробелами');
        }

        $availableSpaces = $this->dataPrepare($initAvailableSpaces);
        $arrayOfCars = $this->dataPrepare($setOfCars);

        if (count($availableSpaces) > count($this->capacityFloors)) {
            throw new Exception ('Количество этажей во входных данных превышает значение
                                  установленное при инициализации');
        }

        foreach ($availableSpaces as $availableSpace) {
            $availableSpace = (int)$availableSpace;
            if ($availableSpace < 0) {
                throw new Exception ('Количество свободных парковочных мест в наборе,
                                      должено быть представлено натуральным числом');
            }
        }

        if (array_sum($availableSpaces) <= 0) {
            throw new Exception ('Нет свободных парковочных мест');
        }

        foreach (array_unique($arrayOfCars) as $car) {
            if (!in_array($car, $this->uniqueTypeCars)) {
                throw new Exception ('Неизвестный тип транспорта во входящих данных');
            }
        }

        $sequence = [];

        foreach ($arrayOfCars as $car) {
            for ($i = count($availableSpaces) - 1; $i >= 0; $i--) {
                if (
                    in_array($car, $this->capacityFloors[$i])
                    && $availableSpaces[$i] > 0
                ) {
                    $availableSpaces[$i] = --$availableSpaces[$i];
                    $sequence[] = 'y';
                    break;
                } else {
                    if ($i <= 0) $sequence[] = 'n';
                }
            }
        }

        return implode(' ', $sequence);
    }

    /**
     * @param string $string
     * @return array
     */
    private function dataPrepare(string $string): array
    {
        $string = trim($string);
        $string = preg_replace('/\s{2,}/', ' ', $string);

        return explode(' ', $string);
    }

    /**
     * Возвращает массив уникальных типов транспортных средств из инициализирующей матрицы
     *
     * @return array
     */
    private function getUniqueTypeCars(): array
    {
        $uniqueTypeCars = [];
        foreach ($this->capacityFloors as $capacityFloor) {
            $uniqueTypeCars = array_merge($uniqueTypeCars, $capacityFloor);
        }

        return array_unique($uniqueTypeCars);
    }
}
