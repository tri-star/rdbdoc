<?php


namespace Dbdg\Plugins;


use Dbdg\Models\DataBase;
use Dbdg\Models\OutputConfig;

interface DocumentWriterPluginInterface extends PluginInterface
{

    public function getWriterName();

    public function write(OutputConfig $outputConfig, DataBase $dataBase);

}
