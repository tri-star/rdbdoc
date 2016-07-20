<?php

use Dbdg\Models\DataBase;
use Dbdg\Models\OutputConfig;
use Dbdg\Models\Table;
use Dbdg\Plugins\DocumentWriterPluginInterface;
use Dbdg\Plugins\PluginManager;

class DocumentWriterExcel implements DocumentWriterPluginInterface
{

    /**
     * generate:document の--formatで指定する際の名前を返します。
     * @return string
     */
    public function getWriterName()
    {
        return 'xlsx';
    }

    /**
     * ドキュメントの生成を実行します。
     * @param OutputConfig $outputConfig
     * @param DataBase $dataBase
     */
    public function write(OutputConfig $outputConfig, DataBase $dataBase)
    {
        $excel = new \PHPExcel();

        //各テーブルのシートを作成
        //目次のシートを生成
        //DBのシートを作成
        //表紙を作成
        //ヘッダ・フッタ、印刷設定

        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        $this->writeTableList($sheet, $dataBase->getTables());


        foreach($dataBase->getTables() as $table) {
            $sheet = $excel->createSheet();
            $this->outputTableSheet($sheet, $table);
        }


        $outputDir = $outputConfig->getOutputDir();
        if(!is_dir($outputDir)) {
            if(!mkdir($outputDir, 0775, true)) {
                throw new \Exception('ディレクトリの生成に失敗しました。' . $outputDir);
            }
        }
        $outputPath = $outputDir . '/definition.xlsx';

        $excel->setActiveSheetIndex(0);

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save($outputPath);
    }

    /**
     * このプラグインの名称を返します。
     * @return string
     */
    public function getName()
    {
        return 'document-writer-excel';
    }

    /**
     * プラグインのインストールを行います。
     *
     * PluginManagerのExtensionPointへのフックなどを行います。
     *
     * @param PluginManager $manager
     * @throws Exception
     */
    public function installPlugin(PluginManager $manager)
    {
        $manager->registerExtensionPoint('document_writer', $this);
    }


    /**
     * @param PHPExcel_Worksheet $sheet
     * @param Table[] $tables
     * @throws \PHPExcel_Exception
     */
    private function writeTableList(PHPExcel_Worksheet $sheet, $tables) {

        $sheet->setTitle('テーブル一覧');

        $sheet->setCellValue('A1', 'テーブル一覧');
        $sheet->getCell('A1')->getStyle()->getFont()->applyFromArray(array(
            'bold' => true,
            'size' => 20,
        ));
        $sheet->getRowDimension(1)->setRowHeight(25);


        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'テーブル名');
        $sheet->setCellValue('C3', '論理名');
        $sheet->setCellValue('D3', 'ENGINE');
        $sheet->setCellValue('E3', 'ROWS');
        $sheet->setCellValue('F3', 'AUTO_INCREMENT');
        $sheet->setCellValue('G3', '概要');
        $sheet->getStyle('A3:G3')->getBorders()->applyFromArray(array(
            'allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
            )
        ));
        $sheet->getStyle('A3:G3')->getFill()->applyFromArray(array(
            'style' => \PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array(
                'rgb' => 'd9edf7',
            )
        ));


        $rowNo = 4;
        foreach($tables as $index=>$table) {
            $metaData = $table->getMetaData();
            $sheet->setCellValue('A' . $rowNo, $index+1);
            $sheet->setCellValue('B' . $rowNo, $table->getName());
            $sheet->getCell('B' . $rowNo)->getHyperlink()->setUrl("sheet://{$table->getName()}!A1");
            $sheet->setCellValue('C' . $rowNo, $table->getLogicalName());
            $sheet->setCellValue('D' . $rowNo, sprintf('%s (%s)', $metaData['engine'], $metaData['row_format']));
            $sheet->setCellValue('E' . $rowNo, $metaData['rows']);
            $sheet->setCellValue('F' . $rowNo, $metaData['auto_increment']);
            $sheet->setCellValue('G' . $rowNo, $table->getDescription());

            $sheet->getCell('E' . $rowNo)->getStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getCell('E' . $rowNo)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getCell('F' . $rowNo)->getStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getCell('F' . $rowNo)->getStyle()->getNumberFormat()->setFormatCode('#,##0');
            $rowNo++;
        }
        $sheet->getStyle('A4:G'.($rowNo-1))->getBorders()->applyFromArray(array(
            'allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
            )
        ));

        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(50);
    }


    private function outputTableSheet(PHPExcel_Worksheet $sheet, Table $table) {

        $sheet->setTitle($table->getName());

        //テーブル情報
        // - テーブル名
        // - 論理名
        // - 説明

        $sheet->setCellValue('A1', 'テーブル情報');
        $sheet->getCell('A1')->getStyle()->getFont()->applyFromArray(array(
            'bold' => true,
            'size' => 20,
        ));

        $sheet->setCellValue('A3', 'テーブル名');
        $sheet->setCellValue('B3', $table->getName());
        $sheet->setCellValue('A4', '論理名');
        $sheet->setCellValue('B4', $table->getLogicalName());
        $sheet->setCellValue('A5', '説明');
        $sheet->setCellValue('B5', $table->getDescription());

        $sheet->mergeCells('B3:F3');
        $sheet->mergeCells('B4:F4');
        $sheet->mergeCells('B5:F5');

        $sheet->getStyle('A3:F5')->getBorders()->applyFromArray(array(
            'allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
            )
        ));
        $sheet->getStyle('A3:A5')->getFill()->applyFromArray(array(
            'style' => \PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array(
                'rgb' => 'd9edf7',
            )
        ));

        //カラム一覧
        $sheet->setCellValue('A8', 'カラム一覧');
        $sheet->getCell('A8')->getStyle()->getFont()->applyFromArray(array(
            'bold' => true,
            'size' => 20,
        ));
        $sheet->setCellValue('A10', 'No');
        $sheet->setCellValue('B10', 'カラム名');
        $sheet->setCellValue('C10', '論理名');
        $sheet->setCellValue('D10', 'データ型');
        $sheet->setCellValue('E10', 'デフォルト値');
        $sheet->setCellValue('F10', '説明');
        $rowNo = 11;
        foreach($table->getColumns() as $idx=>$column) {
            $sheet->setCellValue('A' . $rowNo, $idx+1);
            $sheet->setCellValue('B' . $rowNo, $column->getName());
            $sheet->setCellValue('C' . $rowNo, $column->getLogicalName());
            $sheet->setCellValue('D' . $rowNo, $column->getType());
            $sheet->setCellValue('E' . $rowNo, $column->getDefault());
            $sheet->setCellValue('F' . $rowNo, $column->getDescription());
            $sheet->getCell('F' . $rowNo)->getStyle()->getAlignment()->setWrapText(true);
            $sheet->getCell('F' . $rowNo)->getStyle()->getAlignment()->setShrinkToFit(false);


            $sheet->getRowDimension($rowNo)->setRowHeight(-1);
            $rowNo++;
        }
        $sheet->getStyle('A10:F'.($rowNo-1))->getBorders()->applyFromArray(array(
            'allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
            )
        ));
        $sheet->getStyle('A10:F10')->getFill()->applyFromArray(array(
            'style' => \PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array(
                'rgb' => 'd9edf7',
            )
        ));

        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(8)->setRowHeight(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(50);

    }
}
