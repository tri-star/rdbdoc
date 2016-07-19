<?php


use Dbdg\Adapters\Connectors\ConnectorMysql;

class ConnectorMysqlTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    function test() {

        $connector = new ConnectorMysql();
        $connector->init(array(
            'host' => '127.0.0.1',
            'port' => '13307',
            'user' => 'root',
            'pass' => '',
            'db' => 'test',
        ));

        $result = $connector->getColumns('test', 'a');
        var_export($result);
    }

}