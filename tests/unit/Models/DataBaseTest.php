<?php


use Dbdg\Models\DataBase;
use Dbdg\Models\Table;

class DataBaseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function beSureDetectTableExists()
    {
        $dataBase = new DataBase();


        $table = new Table();
        $table->setName('abc');
        $dataBase->addTable($table);

        $table2 = new Table();
        $table2->setName('def');
        $dataBase->addTable($table2);

        $this->assertTrue($dataBase->isTableExists('abc'));
        $this->assertTrue($dataBase->isTableExists('def'));
        $this->assertFalse($dataBase->isTableExists('abcd'));
    }

}
