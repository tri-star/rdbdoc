<?php

namespace Dbdg\InputPorts\Connectors;


use Dbdg\Models\Column;
use Dbdg\Models\Table;

class ConnectorMysql implements ConnectorInterface
{

    /**
     * @var \PDO
     */
    private $db;


    private $connectionInfo;


    public function __construct()
    {
        $this->db = null;
    }


    public function init($connectionInfo)
    {
        $this->connectionInfo = $connectionInfo;
    }


    public function getTables($databaseName)
    {
        $db = $this->getConnection();
        $sql = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA=:db_name ORDER BY TABLE_NAME";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            'db_name' => $databaseName
        ));
        $tableRows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $tables = array();
        foreach($tableRows as $tableRow) {
            $table = new Table();
            $table->setName($tableRow['TABLE_NAME']);
            $table->setMetaData(array(
                'engine'         => $tableRow['ENGINE'],
                'row_format'     => $tableRow['ROW_FORMAT'],
                'rows'           => $tableRow['TABLE_ROWS'],
                'avg_row_length' => $tableRow['AVG_ROW_LENGTH'],
                'data_length'    => $tableRow['DATA_LENGTH'],
                'auto_increment' => $tableRow['AUTO_INCREMENT'],
                'created'        => $tableRow['CREATE_TIME'],
                'create_options' => $tableRow['CREATE_OPTIONS'],
            ));
            $table->setComment($tableRow['TABLE_COMMENT']);
            $tables[] = $table;
        }
        return $tables;
/*
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'test',
    'TABLE_NAME' => 'a',
    'TABLE_TYPE' => 'BASE TABLE',
    'ENGINE' => 'InnoDB',
    'VERSION' => '10',
    'ROW_FORMAT' => 'Compact',
    'TABLE_ROWS' => '0',
    'AVG_ROW_LENGTH' => '0',
    'DATA_LENGTH' => '16384',
    'MAX_DATA_LENGTH' => '0',
    'INDEX_LENGTH' => '0',
    'DATA_FREE' => '0',
    'AUTO_INCREMENT' => '1',
    'CREATE_TIME' => '2016-07-12 14:55:01',
    'UPDATE_TIME' => NULL,
    'CHECK_TIME' => NULL,
    'TABLE_COLLATION' => 'latin1_swedish_ci',
    'CHECKSUM' => NULL,
    'CREATE_OPTIONS' => '',
    'TABLE_COMMENT' => '',
  ),
*/
    }

    public function getColumns($databaseName, $tableName)
    {
        $db = $this->getConnection();
        $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=:db_name AND TABLE_NAME=:table_name ORDER BY ORDINAL_POSITION";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            'db_name' => $databaseName,
            'table_name' => $tableName
        ));
        $columnRows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $columns = array();
        foreach($columnRows as $columnRow) {
            $column = new Column();
            $column->setName($columnRow['COLUMN_NAME']);
            $column->setPosition($columnRow['ORDINAL_POSITION']);
            $column->setType($columnRow['COLUMN_TYPE']);
            $column->setDefault($columnRow['COLUMN_DEFAULT']);
            $column->setNullable($columnRow['IS_NULLABLE'] == 'YES' ? true : false);
            $column->setMetaData(array(
                'character_set' => $columnRow['CHARACTER_SET_NAME'],
                'collation'     => $columnRow['COLLATION_NAME'],
                'extra'         => $columnRow['EXTRA'],
            ));
            $column->setComment($columnRow['COLUMN_COMMENT']);

            $columns[] = $column;

        }
        return $columns;

/*
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'test',
    'TABLE_NAME' => 'a',
    'COLUMN_NAME' => 'id',
    'ORDINAL_POSITION' => '1',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'int',
    'CHARACTER_MAXIMUM_LENGTH' => NULL,
    'CHARACTER_OCTET_LENGTH' => NULL,
    'NUMERIC_PRECISION' => '10',
    'NUMERIC_SCALE' => '0',
    'DATETIME_PRECISION' => NULL,
    'CHARACTER_SET_NAME' => NULL,
    'COLLATION_NAME' => NULL,
    'COLUMN_TYPE' => 'int(10) unsigned',
    'COLUMN_KEY' => 'PRI',
    'EXTRA' => 'auto_increment',
    'PRIVILEGES' => 'select,insert,update,references',
    'COLUMN_COMMENT' => '',
*/
    }


    public function getConnection() {
        if($this->db) {
            return $this->db;
        }

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s',
            $this->connectionInfo['host'],
            $this->connectionInfo['port'],
            $this->connectionInfo['db']
        );

        $this->db = new \PDO($dsn, $this->connectionInfo['user'], $this->connectionInfo['pass']);


        return $this->db;
    }
}
