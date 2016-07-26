<?php

namespace Dbdg\Adapters\Connectors;


use Dbdg\Models\Column;
use Dbdg\Models\ConnectionConfig;
use Dbdg\Models\Index;
use Dbdg\Models\Table;

class ConnectorMysql implements ConnectorInterface
{

    /**
     * @var \PDO
     */
    private $db;


    /**
     * @var ConnectionConfig
     */
    private $connectionConfig;


    public function __construct()
    {
        $this->db = null;
    }


    public function init(ConnectionConfig $connectionConfig)
    {
        $this->connectionConfig = $connectionConfig;
    }


    public function getTables($databaseName)
    {
        $db = $this->getConnection();
        $sql = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA=:db_name ORDER BY TABLE_NAME";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            'db_name' => $databaseName
        ));
        $tableRows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $tables = array();
        foreach($tableRows as $tableRow) {
            $table = new Table();
            $table->setName($tableRow['TABLE_NAME']);
            $table->setMetaData(array(
                'engine'         => $tableRow['ENGINE'],
                'row_format'     => $tableRow['ROW_FORMAT'],
                'rows'           => $tableRow['TABLE_ROWS'],
                'avg_row_length' => $tableRow['AVG_ROW_LENGTH'],
                'data_length'    => $tableRow['DATA_LENGTH'],
                'auto_increment' => $tableRow['AUTO_INCREMENT'],
                'created'        => $tableRow['CREATE_TIME'],
                'create_options' => $tableRow['CREATE_OPTIONS'],
            ));
            $table->setComment($tableRow['TABLE_COMMENT']);
            $tables[] = $table;
        }
        return $tables;
/*
  array (
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'test',
    'TABLE_NAME' => 'a',
    'TABLE_TYPE' => 'BASE TABLE',
    'ENGINE' => 'InnoDB',
    'VERSION' => '10',
    'ROW_FORMAT' => 'Compact',
    'TABLE_ROWS' => '0',
    'AVG_ROW_LENGTH' => '0',
    'DATA_LENGTH' => '16384',
    'MAX_DATA_LENGTH' => '0',
    'INDEX_LENGTH' => '0',
    'DATA_FREE' => '0',
    'AUTO_INCREMENT' => '1',
    'CREATE_TIME' => '2016-07-12 14:55:01',
    'UPDATE_TIME' => NULL,
    'CHECK_TIME' => NULL,
    'TABLE_COLLATION' => 'latin1_swedish_ci',
    'CHECKSUM' => NULL,
    'CREATE_OPTIONS' => '',
    'TABLE_COMMENT' => '',
  ),
*/
    }

    public function getColumns($databaseName, $tableName)
    {
        $db = $this->getConnection();
        $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=:db_name AND TABLE_NAME=:table_name ORDER BY ORDINAL_POSITION";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            'db_name' => $databaseName,
            'table_name' => $tableName
        ));
        $columnRows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $columns = array();
        foreach($columnRows as $columnRow) {
            $column = new Column();
            $column->setName($columnRow['COLUMN_NAME']);
            $column->setPosition($columnRow['ORDINAL_POSITION']);
            $column->setType($columnRow['COLUMN_TYPE']);
            $column->setDefault($columnRow['COLUMN_DEFAULT']);
            $column->setNullable($columnRow['IS_NULLABLE'] == 'YES' ? true : false);
            $column->setMetaData(array(
                'character_set' => $columnRow['CHARACTER_SET_NAME'],
                'collation'     => $columnRow['COLLATION_NAME'],
                'extra'         => $columnRow['EXTRA'],
            ));
            $column->setComment($columnRow['COLUMN_COMMENT']);

            $columns[] = $column;

        }
        return $columns;

/*
    'TABLE_CATALOG' => 'def',
    'TABLE_SCHEMA' => 'test',
    'TABLE_NAME' => 'a',
    'COLUMN_NAME' => 'id',
    'ORDINAL_POSITION' => '1',
    'COLUMN_DEFAULT' => NULL,
    'IS_NULLABLE' => 'NO',
    'DATA_TYPE' => 'int',
    'CHARACTER_MAXIMUM_LENGTH' => NULL,
    'CHARACTER_OCTET_LENGTH' => NULL,
    'NUMERIC_PRECISION' => '10',
    'NUMERIC_SCALE' => '0',
    'DATETIME_PRECISION' => NULL,
    'CHARACTER_SET_NAME' => NULL,
    'COLLATION_NAME' => NULL,
    'COLUMN_TYPE' => 'int(10) unsigned',
    'COLUMN_KEY' => 'PRI',
    'EXTRA' => 'auto_increment',
    'PRIVILEGES' => 'select,insert,update,references',
    'COLUMN_COMMENT' => '',
*/
    }


    public function getIndices($dataBaseName, $tableName)
    {
        //information_schema.STATISTICSからインデックスの情報、
        //及び外部キー関連のテーブルからインデックスの情報を取得する。
        //今のところ、外部キーは複数カラムに対応していない。

        $sql = "SELECT stat.TABLE_SCHEMA, stat.TABLE_NAME, stat.INDEX_NAME, stat.COLUMN_NAME, stat.CARDINALITY, stat.INDEX_TYPE, "
             . "       table_const.CONSTRAINT_TYPE, "
             . "       ref_const.REFERENCED_TABLE_NAME, ref_const.UNIQUE_CONSTRAINT_SCHEMA, ref_const.UNIQUE_CONSTRAINT_NAME, "
             . "       ref_const.MATCH_OPTION, ref_const.UPDATE_RULE, ref_const.DELETE_RULE "
             . "  FROM information_schema.STATISTICS stat "
             . "  LEFT OUTER JOIN information_schema.KEY_COLUMN_USAGE key_usage ON (key_usage.TABLE_NAME=stat.TABLE_NAME AND key_usage.COLUMN_NAME=stat.COLUMN_NAME) "
             . "  LEFT OUTER JOIN information_schema.TABLE_CONSTRAINTS table_const ON (table_const.TABLE_NAME=stat.TABLE_NAME AND table_const.CONSTRAINT_NAME=key_usage.CONSTRAINT_NAME) "
             . "  LEFT OUTER JOIN information_schema.REFERENTIAL_CONSTRAINTS ref_const ON (ref_const.TABLE_NAME=stat.TABLE_NAME AND ref_const.CONSTRAINT_NAME=key_usage.CONSTRAINT_NAME) "
             . " WHERE stat.TABLE_SCHEMA=:db_name stat.TABLE_NAME=:table_name "
             . " ORDER BY stat.TABLE_NAME, stat.INDEX_NAME, key_usage.ORDINAL_POSITION"
        ;

        $db = $this->getConnection();
        $statement = $db->prepare($sql);
        $statement->execute(array(
            'db_name' => $dataBaseName,
            'table_name' => $tableName
        ));
        $indexRows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $tmpIndices = array();
        foreach($indexRows as $row) {

            //複数列カラムの場合、同じインデックス名が複数行存在するため、その点を考慮する
            $indexName = $row['INDEX_NAME'];

            if(isset($tmpIndices[$indexName])) {
                $tmpIndices[$indexName]['column_names'][] = $row['COLUMN_NAME'];
            } else {

                $tmpIndices[$indexName] = array(
                    'index_name'             => $indexName,
                    'column_names'           => array($row['COLUMN_NAME']),
                    'cardinality'            => $row['CARDINALITY'],
                    'index_type'             => $row['INDEX_TYPE'],
                    'constraint_type'        => $row['CONSTRAINT_TYPE'],
                    'referenced_table_name'  => $row['REFERENCED_TABLE_NAME'],
                    'unique_constraint_name' => $row['UNIQUE_CONSTRAINT_NAME'],
                    'match_option'           => $row['MATCH_OPTION'],
                    'update_rule'            => $row['UPDATE_RULE'],
                    'delete_rule'            => $row['DELETE_RULE'],
                );
            }
        }

        $indices = array();
        foreach($tmpIndices as $indexData) {
            $index = new Index();
            $index->initFromArray($indexData);
            $indices[] = $index;
        }
        return $indices;
/*
[{
  "TABLE_SCHEMA": "test",
  "TABLE_NAME": "a",
  "INDEX_NAME": "idx_name",
  "COLUMN_NAME": "name",
  "CARDINALITY": 0,
  "INDEX_TYPE": "BTREE",
  "CONSTRAINT_TYPE": null,
  "REFERENCED_TABLE_NAME": null,
  "UNIQUE_CONSTRAINT_SCHEMA": null,
  "UNIQUE_CONSTRAINT_NAME": null,
  "MATCH_OPTION": null,
  "UPDATE_RULE": null,
  "DELETE_RULE": null
 },
 {
  "TABLE_SCHEMA": "test",
  "TABLE_NAME": "comment",
  "INDEX_NAME": "fk_user_id",
  "COLUMN_NAME": "user_id",
  "CARDINALITY": 0,
  "INDEX_TYPE": "BTREE",
  "CONSTRAINT_TYPE": "FOREIGN KEY",
  "REFERENCED_TABLE_NAME": "a",
  "UNIQUE_CONSTRAINT_SCHEMA": "test",
  "UNIQUE_CONSTRAINT_NAME": "PRIMARY",
  "MATCH_OPTION": "NONE",
  "UPDATE_RULE": "RESTRICT",
  "DELETE_RULE": "CASCADE"
 },
 {
  "TABLE_SCHEMA": "test",
  "TABLE_NAME": "comment",
  "INDEX_NAME": "PRIMARY",
  "COLUMN_NAME": "id",
  "CARDINALITY": 0,
  "INDEX_TYPE": "BTREE",
  "CONSTRAINT_TYPE": "PRIMARY KEY",
  "REFERENCED_TABLE_NAME": null,
  "UNIQUE_CONSTRAINT_SCHEMA": null,
  "UNIQUE_CONSTRAINT_NAME": null,
  "MATCH_OPTION": null,
  "UPDATE_RULE": null,
  "DELETE_RULE": null
 }
]
*/
    }


    public function getConnection() {
        if($this->db) {
            return $this->db;
        }

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s',
            $this->connectionConfig->getHost(),
            $this->connectionConfig->getPort(),
            $this->connectionConfig->getDb()
        );

        $this->db = new \PDO($dsn, $this->connectionConfig->getUser(), $this->connectionConfig->getPassword());


        return $this->db;
    }
}
