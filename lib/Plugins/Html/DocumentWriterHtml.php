<?php

namespace Dbdg\Plugins\Html;


use Dbdg\Models\DataBase;
use Dbdg\Models\OutputConfig;
use Dbdg\OutputPorts\StreamWriters\StreamWriterFile;
use Dbdg\OutputPorts\StreamWriters\StreamWriterInterface;
use Dbdg\Plugins\DocumentWriterPluginInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;

class DocumentWriterHtml implements DocumentWriterPluginInterface
{

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var StreamWriterInterface
     */
    private $writer;

    public function write(OutputConfig $outputConfig, DataBase $dataBase)
    {

        $outputPath = $outputConfig->getOutputDir() . '/index.html';
        $this->writer = new StreamWriterFile($outputPath);


        $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
        $this->twig = new Twig_Environment($loader, array(
        ));

        //データベースの情報(見出しページ)
        $this->renderDatabase($dataBase);

        //目次
        //テーブル一覧

        //各テーブル
        //テーブル説明
        //メタ情報
        //カラム一覧

        //各カラム
        // * 名前
        // * データ型
        // * デフォルト値
        // * 説明


    }

    public function getName()
    {
        return 'document-writer-html';
    }


    private function renderDatabase(DataBase $dataBase)
    {


        $html = $this->twig->render('index.twig.html', array('db' => $dataBase));
        $this->writer->write($html);
    }

}