<?php


namespace Dbdg\Models;


class OutputConfig
{

    private $outputDir;


    public function init($outputDir)
    {
        $this->outputDir = $outputDir;
    }


    public function getOutputDir()
    {
        return $this->outputDir;
    }

}
