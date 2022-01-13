<?php

class Parser
{
    private string $file = "";
    private array $drawSet = [];
    private array $grids = [];

    public function __construct(string $_file)
    {
        $this->file = $_file;
    }

    public function setDrawSet(array $_drawSet): Parser
    {
        $this->drawSet = $_drawSet;
        return $this;
    }

    public function getDrawSet(): array
    {
        return $this->drawSet;
    }

    public function setGrids(array $_grids): Parser
    {
        $this->grids = $_grids;
        return $this;
    }

    public function getGrids(): array
    {
        return $this->grids;
    }

    public function parseFile(string $_file = null)
    {
        $file = $this->file;
        if ($_file !== null) {
            $file = $_file;
        }

        try {
            if (!file_exists($file)) {
                throw new Exception(sprintf('File %s not found.', $file));
            }
            $openedFile = fopen($file, "r");
            if (!$openedFile) {
                throw new Exception('File open failed.');
            }  
            $datas = fread($openedFile, filesize($file));
        } catch (Exception $e) {
            throw new Exception(sprintf('File error %s', $e));
        }

        $explodedFile = explode("--------------", $datas);
        $this->setDrawSet(explode(',', array_shift($explodedFile)));
        $this->setGrids($explodedFile);
    }
}
