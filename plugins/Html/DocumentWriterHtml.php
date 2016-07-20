<?php

use Dbdg\Models\DataBase;
use Dbdg\Models\OutputConfig;
use Dbdg\Utils\StreamWriters\StreamWriterFile;
use Dbdg\Utils\StreamWriters\StreamWriterInterface;
use Dbdg\Plugins\DocumentWriterPluginInterface;
use Dbdg\Plugins\PluginManager;

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


    /**
     * generate:document の--formatで指定する際の名前を返します。
     * @return string
     */
    public function getWriterName()
    {
        return 'html';
    }


    /**
     * ドキュメントの生成を実行します。
     * @param OutputConfig $outputConfig
     * @param DataBase $dataBase
     */
    public function write(OutputConfig $outputConfig, DataBase $dataBase)
    {

        $outputPath = $outputConfig->getOutputDir() . '/index.html';
        $this->writer = new StreamWriterFile($outputPath);


        $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
        $this->twig = new Twig_Environment($loader, array(
        ));

        $this->renderDatabase($dataBase);
    }

    /**
     * このプラグインの名称を返します。
     * @return string
     */
    public function getName()
    {
        return 'document-writer-html';
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


    private function renderDatabase(DataBase $dataBase)
    {


        $html = $this->twig->render('index.twig.html', array('db' => $dataBase));
        $this->writer->write($html);
    }

}
