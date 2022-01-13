<?php
require("./parser.php");
require("./grid.php");

CONST DATA_FILE = "./data/exo/01.txt";

function findTheWinner(array $drawSet, array $grids)
{
    $turnsNumber = -1;

    foreach($grids as $grid) {
        $gridTurnsToWin = $grid->numberOfTurnsToWin($drawSet);

        if ($gridTurnsToWin === -1) {
            continue;
        }
        if ($turnsNumber === -1) {
            $turnsNumber = $gridTurnsToWin;
            $winningGrid = $grid;
            continue;
        }
        if ($gridTurnsToWin < $turnsNumber) {
            $turnsNumber = $gridTurnsToWin;
            $winningGrid = $grid;
        }
    }

    if ($turnsNumber !== -1) {
        echo sprintf("Winning grid is : \n%s\n", $winningGrid);
        echo sprintf("Final score : %s\n", $winningGrid->getScore());
    } else {
        echo sprintf("There is, sadly, no winner\n");
    }
}

function main()
{
    $parser = New Parser(DATA_FILE);
    $grids = [];

    try {
        $parser->parseFile();
    } catch (Exception $e) {
        echo sprintf("Error while trying to access data : %s\n", $e);
        return;
    }

    if (count($parser->getDrawSet()) === 0) {
        echo "No drawSet found on file; please check file format\n";
        return;
    }

    if (count($parser->getGrids()) === 0) {
        echo "No grids found on file; please check file format\n";
        return;
    }

    foreach($parser->getGrids() as $grid) {
        array_push($grids, New Grid($grid));
    }

    findTheWinner($parser->getDrawSet(), $grids);
}

main();
