<?php


use Dbdg\Adapters\TemplateReaders\TemplateReaderYaml;
use Dbdg\Utils\StreamReaders\StreamReaderString;

class TemplateReaderYamlTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {

    }


    /**
     * @test
     */
    public function basicUsage()
    {

        $yaml = <<<YAML
database:
    name: db_name
    desc: database description.
    tables:
        table1:
            name: table1_logical_name
            desc: table1 description.
            columns:
                id: { name: "ID", desc: "ID description." }
                name: { name: "user name", desc: "user name description." }

YAML;


        $streamReader = new StreamReaderString($yaml);
        $templateReader = new TemplateReaderYaml();
        $templateReader->init($streamReader);
        $dataBase = $templateReader->read();

        //TODO: カスタムアサーションを用意する
        $this->assertEquals('db_name', $dataBase->getName());
        $this->assertEquals('database description.', $dataBase->getDescription());

        $tables = $dataBase->getTables();
        $this->assertEquals('table1', $tables[0]->getName());
        $this->assertEquals('table1_logical_name', $tables[0]->getLogicalName());
        $this->assertEquals('table1 description.', $tables[0]->getDescription());

        $columns = $tables[0]->getColumns();
        $this->assertEquals('id', $columns[0]->getName());
        $this->assertEquals('ID', $columns[0]->getLogicalName());
        $this->assertEquals('ID description.', $columns[0]->getDescription());

        $this->assertEquals('name', $columns[1]->getName());
        $this->assertEquals('user name', $columns[1]->getLogicalName());
        $this->assertEquals('user name description.', $columns[1]->getDescription());
    }

}
