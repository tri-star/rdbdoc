<?php


use Dbdg\Models\Column;
use Dbdg\Models\DataBase;
use Dbdg\Models\Table;
use Dbdg\OutputPorts\TemplateWriters\TemplateWriterYaml;

class TemplateWriterYamlTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function test() {

        $templateWriter = new TemplateWriterYaml();


        $dataBase = new Database();
        $dataBase->setName('test');

        $table = new Table();
        $table->setName('a');

        $column = new Column();
        $column->setName('id');
        $table->addColumn($column);

        $column = new Column();
        $column->setName('name');
        $table->addColumn($column);

        $dataBase->addTable($table);

        $templateWriter->write(array(
            'path' => './test.yml',
        ), $dataBase);

    }

}
