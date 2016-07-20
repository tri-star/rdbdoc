<?php

namespace Dbdg\Commands;


use Dbdg\Adapters\Connectors\ConnectorMysql;
use Dbdg\Plugins\PluginManager;
use Dbdg\Utils\StreamReaders\StreamReaderFile;
use Dbdg\Adapters\TemplateReaders\TemplateReaderYaml;
use Dbdg\Models\ConnectionConfig;
use Dbdg\Models\OutputConfig;
use Dbdg\UseCases\GenerateDocument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateDocumentCommand extends Command
{

    /**
     * @var PluginManager
     */
    private $pluginManager;

    private $plugins;

    protected function configure()
    {
        $this->pluginManager = PluginManager::getInstance();
        $plugins = $this->pluginManager->getPlugins('document_writer');

        $this->plugins = array();
        foreach($plugins as $plugin) {
            $pluginName = $plugin->getWriterName();
            $this->plugins[$pluginName] = $plugin;
        }
        $pluginNameList = implode(',', array_keys($this->plugins));

        $this->setName('generate:document')
            ->setDescription('スキーマ情報とテンプレートからドキュメントを生成します')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, '接続先ホスト', 'localhost')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, '接続先ポート', 3306)
            ->addOption('user', null, InputOption::VALUE_OPTIONAL, '接続先ユーザー名')
            ->addOption('input', null, InputOption::VALUE_OPTIONAL, 'スキーマ定義ファイル名', './schema.yaml')
            ->addOption('format', null, InputOption::VALUE_OPTIONAL, "出力フォーマット({$pluginNameList})", 'xlsx')
            ->addArgument('output-dir', InputArgument::OPTIONAL, '出力ディレクトリ', './docs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $input->getOptions();

        $host = $options['host'];
        $port = $options['port'];
        $user = $options['user'];
        $inputFile = $options['input'];
        $format = $options['format'];

        if(!$user) {
            $user = get_current_user();
        }
        if(!isset($this->plugins[$format])) {
            throw new \Exception('無効なフォーマットが指定されました。: ' . $format);
        }

        $outputDir = $input->getArgument('output-dir');

        $questionHelper = $this->getHelper('question');
        $passwordQuestion = new Question('Input password:');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        $password = $questionHelper->ask($input, $output, $passwordQuestion);

        $templateReader = new TemplateReaderYaml();
        $templateReader->init(new StreamReaderFile($inputFile));

        $connectionConfig = new ConnectionConfig($host, $port, '', $user, $password);
        $connector = new ConnectorMysql();
        $connector->init($connectionConfig);

        $outputConfig = new OutputConfig();
        $outputConfig->init($outputDir);

        $docWriter = $this->plugins[$format];

        $generateDocument = new GenerateDocument();
        $generateDocument->generate($outputConfig, $templateReader, $connector, $docWriter);
    }

}
