<?php

namespace Dbdg\Commands;


use Dbdg\InputPorts\Connectors\ConnectorMysql;
use Dbdg\InputPorts\StreamReaders\StreamReaderFile;
use Dbdg\InputPorts\TemplateReaders\TemplateReaderYaml;
use Dbdg\Models\ConnectionConfig;
use Dbdg\Models\DataBase;
use Dbdg\Models\OutputConfig;
use Dbdg\Plugins\Html\DocumentWriterHtml;
use Dbdg\UseCases\GenerateDocument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateDocumentCommand extends Command
{

    protected function configure()
    {
        $this->setName('generate:document')
            ->setDescription('スキーマ情報とテンプレートからドキュメントを生成します')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, '接続先ホスト', 'localhost')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, '接続先ポート', 3306)
            ->addOption('user', null, InputOption::VALUE_OPTIONAL, '接続先ユーザー名')
            ->addOption('input', null, InputOption::VALUE_OPTIONAL, 'スキーマ定義ファイル名', './schema.yaml')
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

        if(!$user) {
            $user = get_current_user();
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
        $docWriter = new DocumentWriterHtml();

        $generateDocument = new GenerateDocument();
        $generateDocument->generate($outputConfig, $templateReader, $connector, $docWriter);
    }

}
