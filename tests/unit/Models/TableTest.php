<?php


use Dbdg\Models\Column;
use Dbdg\Models\Table;

class TableTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function beSureDetectTableExists()
    {
        $table = new Table();

        $column = new Column();
        $column->setName('id');
        $table->addColumn($column);

        $column2 = new Column();
        $column2->setName('name');
        $table->addColumn($column2);

        $this->assertTrue($table->isColumnExists('id'));
        $this->assertTrue($table->isColumnExists('name'));
        $this->assertFalse($table->isColumnExists('name2'));

    }

}
