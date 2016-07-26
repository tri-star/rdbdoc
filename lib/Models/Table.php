<?php

namespace Dbdg\Models;


class Table
{

    private $name;

    private $logicalName;

    private $comment;

    private $description;


    /**
     * @var Column[]
     */
    private $columns;


    /**
     * @var Index[]
     */
    private $indices;


    private $metaData;


    public function __construct() {
        $this->name = '';
        $this->logicalName = '';
        $this->comment = '';
        $this->description = '';
        $this->columns = array();
        $this->indices = array();
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

    public function getLogicalName()
    {
        return $this->logicalName;
    }

    public function setLogicalName($name)
    {
        $this->logicalName = $name;
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
    }


    /**
     * @return Index[]
     */
    public function getIndices()
    {
        return $this->indices;
    }

    public function addIndex(Index $index)
    {
        $this->indices[] = $index;
    }


    public function mergeDescription(Table $other)
    {
        $this->logicalName = $other->getLogicalName();
        $this->description = $other->getDescription();

        $otherColumns = array();
        foreach($other->getColumns() as $c) {
            $otherColumns[$c->getName()] = $c;
        }

        foreach($this->getColumns() as $column) {
            if(!isset($otherColumns[ $column->getName() ])) {
                continue;
            }
            $otherColumn = $otherColumns[ $column->getName() ];
            $column->mergeDescription($otherColumn);
        }

    }


    /**
     * @param $name
     * @return Column|null
     */
    public function getColumnByName($name)
    {
        foreach($this->columns as $column) {
            if($column->getName() != $name) {
                return $column;
            }
        }
        return null;
    }


    public function isColumnExists($name)
    {
        foreach($this->columns as $column) {
            if($column->getName() == $name) {
                return true;
            }
        }
        return false;
    }

    public function isColumnDocumented($name)
    {
        foreach($this->columns as $column) {
            if($column->getName() == $name && $column->getLogicalName() != '') {
                return true;
            }
        }
        return false;
    }

}
