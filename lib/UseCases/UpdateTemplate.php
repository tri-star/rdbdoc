<?php


namespace Dbdg\UseCases;


use Dbdg\Adapters\Connectors\ConnectorInterface;
use Dbdg\Adapters\TemplateReaders\TemplateReaderInterface;
use Dbdg\Models\DataBase;
use Dbdg\Adapters\TemplateWriters\TemplateWriterInterface;

class UpdateTemplate
{

    public function __construct()
    {

    }

    public function updateTemplate(ConnectorInterface $connector, TemplateReaderInterface $templateReader, TemplateWriterInterface $templateWriter)
    {

        $originalDataBase = $templateReader->read();

        $dataBase = new DataBase();
        $dataBase->setName($originalDataBase->getName());

        $tables = $connector->getTables($dataBase->getName());

        foreach($tables as $table) {
            $columns = $connector->getColumns($dataBase->getName(), $table->getName());
            foreach($columns as $column) {
                $table->addColumn($column);
            }
            $dataBase->addTable($table);
        }
        $dataBase->mergeDescription($originalDataBase);

        $templateWriter->write($dataBase);
    }


}
