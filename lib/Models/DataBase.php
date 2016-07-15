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
}
