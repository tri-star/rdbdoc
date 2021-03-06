<?php

namespace Dbdg\Models;


class DataBase
{

    private $name;


    private $description;


    /**
     * @var Table[]
     */
    private $tables;

    public function __construct()
    {
        $this->name = '';
        $this->description = '';
        $this->tables = array();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }


    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @return Table[]
     */
    public function getTables()
    {
        return $this->tables;
    }


    public function addTable(Table $table)
    {
        $this->tables[] = $table;
    }


    public function mergeDescription(DataBase $other)
    {
        $this->description = $other->getDescription();

        $otherTables = array();
        foreach($other->getTables() as $t) {
            $otherTables[$t->getName()] = $t;
        }

        foreach($this->tables as $table) {
            if(!isset($otherTables[ $table->getName() ])) {
                continue;
            }
            $otherTable = $otherTables[ $table->getName() ];
            $table->mergeDescription($otherTable);
        }
    }


    public function isTableExists($name)
    {
        foreach($this->tables as $table) {
            if($table->getName() == $name) {
                return true;
            }
        }
        return false;
    }


    public function isColumneExists($tableName, $columnName)
    {
        foreach($this->tables as $table) {
            if($table->getName() != $tableName) {
                continue;
            }

            if($table->isColumnExists($columnName)) {
                return true;
            }

        }
        return false;
    }


    public function isTableDocumented($name)
    {
        foreach($this->tables as $table) {
            if($table->getName() == $name && $table->getLogicalName() != '') {
                return true;
            }
        }
        return false;
    }


    public function isColumneDocumented($tableName, $columnName)
    {
        foreach($this->tables as $table) {
            if($table->getName() != $tableName) {
                continue;
            }

            if($table->isColumnDocumented($columnName)) {
                return true;
            }

        }
        return false;
    }


}
