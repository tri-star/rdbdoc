<?php

namespace Dbdg\Plugins\Excel;

use Dbdg\Models\DataBase;
use Dbdg\Models\OutputConfig;
use Dbdg\Models\Table;
use Dbdg\Plugins\DocumentWriterPluginInterface;
use Dbdg\Plugins\PluginManager;
use PHPExcel_Worksheet;

class DocumentWriterExcel implements DocumentWriterPluginInterface
{

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
        $sheet->setCellValue('A1', 'test');
        $sheet->setTitle('Sheet_title');

        foreach($dataBase->getTables() as $table) {
            $sheet = $excel->createSheet();
            $this->outputTableSheet($sheet, $table);
        }


        $outputDir = $outputConfig->getOutputDir();
        if(!is_dir($outputDir)) {
            if(!mkdir($outputDir, true, 0775)) {
                throw new \Exception('ディレクトリの生成に失敗しました。' . $outputDir);
            }
        }
        $outputPath = $outputDir . '/definition.xlsx';

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save($outputPath);
    }

    public function getName()
    {
        return 'document-writer-excel';
    }

    public function installPlugin(PluginManager $manager)
    {
        $manager->registerExtensionPoint('document_writer', $this);
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

        $sheet->getStyle('A3:B5')->getBorders()->applyFromArray(array(
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
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(50);

    }
}
