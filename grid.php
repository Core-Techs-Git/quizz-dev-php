<?php

class Grid
{
    private string $defaultGrid = "";
    private array $rows = [];
    private array $columns = [];
    private int $score = 0;

    public function __construct(string $_values)
    {
        if (strlen($_values) == 0) {
            return;
        }
        $this->defaultGrid = $_values;
        $this->parseValues($_values);
        $this->buildColumns();
    }

    public function parseValues(string $_values)
    {
        $values = explode("\n", $_values);

        for ($i = 1; $i <= 5; $i++) {
            //Deleting multiple spaces for one digit numbers
            $row = trim(preg_replace('/ +/', ' ', $values[$i]));

            $this->rows[] = explode(" ", $row);
        }
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function buildColumns(): Grid
    {
        $rows = $this->getRows();

        for($i=0; $i < 5; $i++) {
            $column = [];
            foreach($rows as $row) {
                $column[] = $row[$i];
            }
            $this->columns[] = $column;
        }

        return $this;
    }

    private function elementsAreCovered(array $set, array $elements): bool
    {
        foreach($elements as $element) {
            $covered_numbers = 0;
            foreach($element as $number) {
                if (array_search($number, $set) !== false) {
                    $covered_numbers++;
                }
            }
            if ($covered_numbers === 5) {
                return true;
            }
        }
        
        return false;
    }

    public function computeScore(array $set): Grid
    {
        $allUnmarked = 0;

        foreach($this->rows as $row) {
            foreach($row as $number) {
                if (array_search($number, $set) === false) {
                    $allUnmarked += $number;
                }
            }
        }

        $this->score = $allUnmarked * $set[count($set) - 1];
        return $this;
    }

    public function numberOfTurnsToWin(array $set): int
    {
        $turn = 0;
        $drawn = [];

        foreach($set as $lastDrawn) {
            array_push($drawn, $lastDrawn);
            if (
                $this->elementsAreCovered($drawn, $this->rows) ||
                $this->elementsAreCovered($drawn, $this->columns)
            ) {
                $this->computeScore($drawn);
                return $turn;
            }
            $turn ++;
        }

        return -1;
    }

    public function __toString(): string
    {
        return $this->defaultGrid;
    }
}
