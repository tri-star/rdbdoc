<?php

namespace Dbdg\InputPorts\Connectors;


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
        $sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=:db_name";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            'db_name' => $databaseName
        ));
        return $statement->fetchAll(\PDO::FETCH_ASSOC);

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

    public function getColumns($tableName)
    {
        // TODO: Implement getColumns() method.
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
