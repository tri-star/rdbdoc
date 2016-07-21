<?php

namespace Dbdg\Test\Adapters;


use Dbdg\Adapters\Connectors\ConnectorInterface;
use Dbdg\Models\Column;
use Dbdg\Models\ConnectionConfig;
use Dbdg\Models\DataBase;
use Dbdg\Models\Table;

class ConnectorStub implements ConnectorInterface
{

    /**
     * @var DataBase
     */
    private $dataBase;

    public function __construct(DataBase $dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function init(ConnectionConfig $connectionConfig)
    {
    }

    /**
     * @param $databaseName
     * @return Table[]
     */
    public function getTables($databaseName)
    {
        return $this->dataBase->getTables();
    }

    /**
     * @param string $databaseName
     * @param string $tableName
     * @return Column[]
     */
    public function getColumns($databaseName, $tableName)
    {

        foreach($this->dataBase->getTables() as $table) {

            if($table->getName() != $tableName) {
                continue;
            }
            return $table->getColumns();
        }
        return array();
    }
}
