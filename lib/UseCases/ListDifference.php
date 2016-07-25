<?php


namespace Dbdg\UseCases;


use Dbdg\Adapters\Connectors\ConnectorInterface;
use Dbdg\Adapters\TemplateReaders\TemplateReaderInterface;
use Dbdg\Models\DataBase;
use Dbdg\Utils\StreamWriters\StreamWriterInterface;

class ListDifference
{

    public function __construct()
    {

    }

    public function listDifference(ConnectorInterface $connector, TemplateReaderInterface $templateReader, StreamWriterInterface $streamWriter)
    {

        //テンプレート情報からスキーマを構築する
        $templateDefinition = $templateReader->read();
        $dataBaseName = $templateDefinition->getName();

        //実際のDBからスキーマ情報を構築する
        $realDataBase = new DataBase();
        $realDataBase->setName($dataBaseName);
        foreach($connector->getTables($dataBaseName) as $table) {

            $columns = $connector->getColumns($dataBaseName, $table->getName());
            foreach($columns as $column) {
                $table->addColumn($column);
            }

            $realDataBase->addTable($table);
        }


        //TODO: 複数種類のフォーマットをサポートする

        //DB側にのみ存在するテーブル、カラム情報を列挙する
        $tablesNotDocumented = $this->listTablesNotDocumented($realDataBase, $templateDefinition);
        $columnsNotDocumented = $this->listColumnsNotDocumented($realDataBase, $templateDefinition);
        $streamWriter->write("tables_not_documented:\n");
        foreach($tablesNotDocumented as $tableName) {
            $streamWriter->write("    - {$tableName}\n");
        }
        $streamWriter->write("columns_not_documented:\n");
        foreach($columnsNotDocumented as $columnName) {
            $streamWriter->write("    - {$columnName}\n");
        }

        //スキーマ側にのみ存在するテーブル、カラム情報を列挙する
        $tablesOnlyOnTemplateDefinition = $this->listTablesOnlyOnBaseDataBase($templateDefinition, $realDataBase);
        $columnsOnlyOnTemplateDefinition = $this->listColumnsOnlyOnBaseDataBase($templateDefinition, $realDataBase);
        $streamWriter->write("tables_only_on_template_definition:\n");
        foreach($tablesOnlyOnTemplateDefinition as $tableName) {
            $streamWriter->write("    - {$tableName}\n");
        }
        $streamWriter->write("columns_only_on_template_definition:\n");
        foreach($columnsOnlyOnTemplateDefinition as $columnName) {
            $streamWriter->write("    - {$columnName}\n");
        }

    }


    private function listTablesOnlyOnBaseDataBase(DataBase $baseDataBase, DataBase $otherDataBase)
    {
        $onlyOnDataBaseList = array();
        foreach($baseDataBase->getTables() as $table) {
            $tableName = $table->getName();
            if(!$otherDataBase->isTableExists($tableName)) {
                $onlyOnDataBaseList[] = $tableName;
            }
        }

        return $onlyOnDataBaseList;
    }

    private function listColumnsOnlyOnBaseDataBase(DataBase $baseDataBase, DataBase $otherDataBase)
    {
        $onlyOnDataBaseList = array();
        foreach($baseDataBase->getTables() as $table) {
            $tableName = $table->getName();
            if(!$otherDataBase->isTableExists($tableName)) {
                continue;
            }
            foreach($table->getColumns() as $column) {
                $columnName = $column->getName();
                if(!$otherDataBase->isColumneExists($tableName, $columnName)) {
                    $onlyOnDataBaseList[] = "{$tableName}.{$columnName}";
                }
            }
        }

        return $onlyOnDataBaseList;
    }


    private function listTablesNotDocumented(DataBase $baseDataBase, DataBase $otherDataBase)
    {
        $onlyOnDataBaseList = array();
        foreach($baseDataBase->getTables() as $table) {
            $tableName = $table->getName();
            if(!$otherDataBase->isTableDocumented($tableName)) {
                $onlyOnDataBaseList[] = $tableName;
            }
        }

        return $onlyOnDataBaseList;
    }

    private function listColumnsNotDocumented(DataBase $baseDataBase, DataBase $otherDataBase)
    {
        $onlyOnDataBaseList = array();
        foreach($baseDataBase->getTables() as $table) {
            $tableName = $table->getName();
            if(!$otherDataBase->isTableDocumented($tableName)) {
                continue;
            }
            foreach($table->getColumns() as $column) {
                $columnName = $column->getName();
                if(!$otherDataBase->isColumneDocumented($tableName, $columnName)) {
                    $onlyOnDataBaseList[] = "{$tableName}.{$columnName}";
                }
            }
        }

        return $onlyOnDataBaseList;
    }

}
