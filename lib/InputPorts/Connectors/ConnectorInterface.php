<?php

namespace Dbdg\InputPorts\Connectors;


interface ConnectorInterface {


    public function init($connectionInfo);

    public function getTables($databaseName);


    public function getColumns($tableName);

}
