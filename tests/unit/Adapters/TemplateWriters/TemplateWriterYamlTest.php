<?php


use Dbdg\Adapters\TemplateWriters\TemplateWriterYaml;
use Dbdg\Models\Column;
use Dbdg\Models\DataBase;
use Dbdg\Models\Table;
use Dbdg\Utils\StreamWriters\StreamWriterString;
use Dbdg\Utils\StringBuffer;

class TemplateWriterYamlTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function basicUsage()
    {

        $buffer = new StringBuffer('');
        $streamWriter = new StreamWriterString($buffer);

        $templateWriter = new TemplateWriterYaml();
        $templateWriter->init($streamWriter);

        $dataBase = new DataBase();
        $dataBase->setName('test_db');
        $dataBase->setDescription('test_db description.');

        $table = new Table();
        $table->setName('test_table');
        $table->setLogicalName('table_logical_name');
        $table->setDescription('test_table description.');

        $column = new Column();
        $column->setName('id');
        $column->setLogicalName('ID');
        $column->setDescription('id description.');

        $column2 = new Column();
        $column2->setName('name');
        $column2->setLogicalName('user name');
        $column2->setDescription('name description.');

        $dataBase->addTable($table);
        $table->addCOlumn($column);
        $table->addCOlumn($column2);

        $templateWriter->write($dataBase);

        $result = $buffer->get();
        $expected = <<<YAML
database:
    name: test_db
    desc: 'test_db description.'
    tables:
        test_table:
            name: table_logical_name
            desc: 'test_table description.'
            columns:
                id: { name: ID, desc: 'id description.' }
                name: { name: 'user name', desc: 'name description.' }

YAML;

        $this->assertEquals($expected, $result);

    }

}
