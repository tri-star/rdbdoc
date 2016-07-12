<?php


use Dbdg\InputPorts\Connectors\ConnectorMysql;

class ConnectorMysqlTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    function test() {

        $connector = new ConnectorMysql();
        $connector->init(array(
            'host' => '127.0.0.1',
            'port' => '13306',
            'user' => 'root',
            'pass' => 'root',
            'db' => 'test',
        ));

        $result = $connector->getTables('test');
        var_export($result);
    }

}