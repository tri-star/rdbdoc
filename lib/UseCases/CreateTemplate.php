<?php


namespace Dbdg\UseCases;


use Dbdg\InputPorts\Connectors\ConnectorInterface;
use Dbdg\Models\ConnectionConfig;
use Dbdg\Models\DataBase;
use Dbdg\OutputPorts\StreamWriters\StreamWriterInterface;
use Dbdg\OutputPorts\TemplateWriters\TemplateWriterYaml;

class CreateTemplate
{

    public function __construct()
    {

    }

    public function createTemplate(ConnectionConfig $connectionConfig, ConnectorInterface $connector, StreamWriterInterface $streamWriter)
    {
        $connector->init($connectionConfig);

        $dataBase = new DataBase();
        $dataBase->setName = $connectionConfig->getDb();

        $tables = $connector->getTables($dataBase->getName());

        foreach($tables as $table) {
            $columns = $connector->getColumns($dataBase->getName(), $table->getName());
            foreach($columns as $column) {
                $table->addColumn($column);
            }
            $dataBase->addTable($table);
        }

        $templateWriter = new TemplateWriterYaml();
        $templateWriter->write($streamWriter, $dataBase);
    }

}
