<?php


namespace Dbdg\UseCases;


use Dbdg\Adapters\Connectors\ConnectorInterface;
use Dbdg\Models\DataBase;
use Dbdg\Adapters\TemplateWriters\TemplateWriterInterface;
use Dbdg\Adapters\TemplateWriters\TemplateWriterYaml;

class CreateTemplate
{

    public function __construct()
    {

    }

    public function createTemplate($dbName, ConnectorInterface $connector, TemplateWriterInterface $templateWriter)
    {
        $dataBase = new DataBase();
        $dataBase->setName($dbName);

        $tables = $connector->getTables($dataBase->getName());

        foreach($tables as $table) {
            $columns = $connector->getColumns($dataBase->getName(), $table->getName());
            foreach($columns as $column) {
                $table->addColumn($column);
            }
            $dataBase->addTable($table);
        }

        $templateWriter->write($dataBase);

    }

}
