<?php

namespace Dbdg\InputPorts\TemplateReaders;


use Dbdg\InputPorts\StreamReaders\StreamReaderInterface;
use Dbdg\Models\Column;
use Dbdg\Models\DataBase;
use Dbdg\Models\Table;
use Symfony\Component\Yaml\Yaml;

class TemplateReaderYaml implements TemplateReaderInterface
{

    /**
     * @var StreamReaderInterface
     */
    private $streamReader;

    public function init(StreamReaderInterface $streamReader)
    {
        $this->streamReader = $streamReader;
    }

    public function read()
    {
        $content = '';
        while(!$this->streamReader->isEof()) {
            $content .= $this->streamReader->read();
        }

        $parsed = Yaml::parse($content);

        $dataBase = $this->parseDataBase($parsed);
        return $dataBase;
    }


    /**
     * @return DataBase
     */
    private function parseDataBase($yaml)
    {
        if(!isset($yaml['database'])) {
            throw new \Exception('databaseキーが存在しません。');
        }
        $parsedDataBase = $yaml['database'];

        if(!array_key_exists('name', $parsedDataBase)) {
            throw new \Exception('database.nameキーが存在しません。');
        }
        if(!array_key_exists('tables', $parsedDataBase)) {
            throw new \Exception('database.tablesキーが存在しません。');
        }

        $description = isset($parsedDataBase['desc']) ? $parsedDataBase['desc'] : '';
        $comment     = isset($parsedDataBase['comment']) ? $parsedDataBase['comment'] : '';

        $dataBase = new DataBase();
        $dataBase->setName($parsedDataBase['name']);
        $dataBase->setDescription($description);

        if(!is_array($parsedDataBase['tables'])) {
            throw new \Exception('database.tablesキーが配列ではありません');
        }

        foreach($parsedDataBase['tables'] as $tableName => $tableEntry) {
            $table = $this->parseTable($tableName, $tableEntry);
            $dataBase->addTable($table);
        }

        return $dataBase;
    }


    /**
     * @return Table
     */
    private function parseTable($tableName, $yaml)
    {
        if(!is_array($yaml)) {
            throw new \Exception($tableName . 'が配列ではありません。');
        }
        if(!array_key_exists('columns', $yaml)) {
            throw new \Exception($tableName . ': columnsキーがありません。');
        }
        if(!is_array($yaml['columns'])) {
            throw new \Exception($tableName . ': columnsキーが配列ではありません。');
        }

        $logicalName = isset($yaml['name']) ? $yaml['name'] : '';
        $description = isset($yaml['desc']) ? $yaml['desc'] : '';

        $table = new Table();
        $table->setName($tableName);
        $table->setLogicalName($logicalName);
        $table->setDescription($description);
        foreach($yaml['columns'] as $columnName => $columnEntry) {
            $column = new Column();

            $logicalName = isset($columnEntry['name']) ? $columnEntry['name'] : '';
            $description = isset($columnEntry['desc']) ? $columnEntry['desc'] : '';

            $column->setName($columnName);
            $column->setLogicalName($logicalName);
            $column->setDescription($description);
            $table->addColumn($column);
        }

        return $table;
    }


}
