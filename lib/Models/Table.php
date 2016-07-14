<?php

namespace Dbdg\Models;


class Table
{

    private $name;

    private $comment;

    private $description;


    private $columns;


    private $indexes;


    private $metaData;


    public function __construct() {
        $this->name = '';
        $this->comment = '';
        $this->description = '';
        $this->columns = array();
        $this->indexes = array();
        $this->metaData = array();
    }


    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }


    public function getMetaData()
    {
        return $this->metaData;
    }

    public function setMetaData($meta)
    {
        $this->metaData = $meta;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
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
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    public function addColumn(Column $column) {
        $this->columns[] = $column;
        //TODO: カラム順にソートする
    }



}
