<?php


namespace Dbdg\UseCases;


use Dbdg\Adapters\Connectors\ConnectorInterface;
use Dbdg\Adapters\TemplateReaders\TemplateReaderInterface;
use Dbdg\Models\DataBase;
use Dbdg\Models\OutputConfig;
use Dbdg\Plugins\DocumentWriterPluginInterface;

class GenerateDocument
{

    /**
     * @param $outputConfig
     * @param $templateReader
     * @param $connector
     * @param $docWriter
     */
    public function generate(OutputConfig $outputConfig, TemplateReaderInterface $templateReader, ConnectorInterface $connector, DocumentWriterPluginInterface $docWriter)
    {
        $originalDataBase = $templateReader->read();
        $dataBase = new DataBase();
        $dataBase->setName($originalDataBase->getName());

        $tables = $connector->getTables($dataBase->getName());
        foreach ($tables as $table) {
            $columns = $connector->getColumns($dataBase->getName(), $table->getName());
            foreach ($columns as $column) {
                $table->addColumn($column);
            }

            $indices = $connector->getIndices($dataBase->getName(), $table->getName());
            foreach($indices as $index) {
                $table->addIndex($index);
            }

            $dataBase->addTable($table);
        }
        $dataBase->mergeDescription($originalDataBase);
        $docWriter->write($outputConfig, $dataBase);
    }

}
