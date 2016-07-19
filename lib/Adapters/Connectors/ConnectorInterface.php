<?php

namespace Dbdg\Adapters\Connectors;


use Dbdg\Models\Column;
use Dbdg\Models\ConnectionConfig;
use Dbdg\Models\Table;

interface ConnectorInterface {


    public function init(ConnectionConfig $connectionConfig);

    /**
     * @param $databaseName
     * @return Table[]
     */
    public function getTables($databaseName);


    /**
     * @param string $databaseName
     * @param string $tableName
     * @return Column[]
     */
    public function getColumns($databaseName, $tableName);

}
