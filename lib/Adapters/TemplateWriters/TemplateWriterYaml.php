<?php

namespace Dbdg\Adapters\TemplateWriters;


use Dbdg\Models\Database;
use Dbdg\Models\Table;
use Dbdg\Utils\StreamWriters\StreamWriterInterface;
use Symfony\Component\Yaml\Yaml;

class TemplateWriterYaml implements TemplateWriterInterface
{

    /**
     * @var StreamWriterInterface
     */
    private $streamWriter;

    public function init(StreamWriterInterface $streamWriter)
    {
        $this->streamWriter = $streamWriter;
    }

    public function write(DataBase $database)
    {

        $tableDefinitions = array();
        foreach($database->getTables() as $table) {
            $tableDefinitions[$table->getName()] = array(
                'name' => $table->getLogicalName(),
                'desc' => $table->getDescription(),
                'columns' => $this->getColumnDefinitions($table),
            );
        }

        $outputData = array(
            'database' => array(
                'name' => $database->getName(),
                'desc' => $database->getDescription(),
                'tables' => $tableDefinitions,
            )
        );

        $yaml = Yaml::dump($outputData, 5);

        $this->streamWriter->write($yaml);
    }



    private function getColumnDefinitions(Table $table)
    {
        $columnDefinitions = array();
        foreach($table->getColumns() as $column) {
            $columnDefinitions[$column->getName()] = array(
                'name' => $column->getLogicalName(),
                'desc' => $column->getDescription(),
            );
        }
        return $columnDefinitions;
    }
}
