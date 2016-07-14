<?php

namespace Dbdg\Models;


class DataBase
{

    private $name;


    private $description;


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
}
