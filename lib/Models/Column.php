<?php

namespace Dbdg\Models;


class Column
{

    private $name;

    private $logicalName;

    private $position;

    private $type;

    private $default;

    private $nullable;

    private $metaData;

    private $comment;

    private $description;


    public function __construct() {
        $this->name = '';
        $this->logicalName = '';
        $this->position = 0;
        $this->type = '';
        $this->default = '';
        $this->nullable = false;
        $this->metaData = array();
        $this->comment = '';
        $this->description = '';
    }


    public function getName() {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getLogicalName() {
        return $this->logicalName;
    }

    public function setLogicalName($logicalName)
    {
        $this->logicalName = $logicalName;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($default)
    {
        $this->default = $default;
    }

    public function isNullable()
    {
        return $this->nullable;
    }

    public function setNullable($nullable)
    {
        $this->nullable = $nullable;
    }

    public function getMetaData()
    {
        return $this->metaData;
    }

    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;
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

    public function mergeDescription(Column $other) {
        $this->logicalName = $other->getLogicalName();
        $this->description = $other->getDescription();
    }

}
