<?php


namespace Dbdg\Models;


class Index
{
    private $indexName;

    private $columnNames;

    private $cardinality;

    private $indexType;

    private $constraintType;

    private $referencedTableName;

    private $uniqueConstraintName;

    private $matchOption;

    private $updateRule;

    private $deleteRule;

    public function __construct()
    {
        $this->indexName = '';
        $this->columnNames = array();
        $this->cardinality = 0;
        $this->indexType = '';
        $this->constraintType = '';
        $this->referencedTableName = '';
        $this->uniqueConstraintName = '';
        $this->matchOption = '';
        $this->updateRule = '';
        $this->deleteRule = '';
    }


    public function initFromArray($array)
    {

        $this->indexName = $this->getArrayValue($array, 'index_name', '');
        $this->columnNames = $this->getArrayValue($array, 'column_names', array());
        $this->cardinality = $this->getArrayValue($array, 'cardinality', '');
        $this->indexType = $this->getArrayValue($array, 'index_type', '');
        $this->constraintType = $this->getArrayValue($array, 'constraint_type', '');
        $this->referencedTableName = $this->getArrayValue($array, 'referenced_table_name', '');
        $this->uniqueConstraintName = $this->getArrayValue($array, 'unique_constraint_name', '');
        $this->matchOption = $this->getArrayValue($array, 'match_option', '');
        $this->updateRule = $this->getArrayValue($array, 'update_rule', '');
        $this->deleteRule = $this->getArrayValue($array, 'delete_rule', '');

    }


    public function getIndexName()
    {
        return $this->indexName;
    }


    public function getColumnNames()
    {
        return $this->columnNames;
    }


    public function getIndexType()
    {
        return $this->indexType;
    }


    public function getConstraintType()
    {
        return $this->constraintType;
    }

    public function getReferencedTableName()
    {
        return $this->referencedTableName;
    }

    public function getUniqueConstraintName()
    {
        return $this->uniqueConstraintName;
    }

    public function getMatchOption()
    {
        return $this->matchOption;
    }

    public function getUpdateRule()
    {
        return $this->updateRule;
    }

    public function getDeleteRule()
    {
        return $this->deleteRule;
    }

    private function getArrayValue($array, $key, $default)
    {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }

}
