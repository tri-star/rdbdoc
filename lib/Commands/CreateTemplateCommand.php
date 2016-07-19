<?php

namespace Dbdg\Commands;


use Dbdg\Adapters\Connectors\ConnectorMysql;
use Dbdg\Models\ConnectionConfig;
use Dbdg\Utils\StreamWriters\StreamWriterFile;
use Dbdg\Adapters\TemplateWriters\TemplateWriterYaml;
use Dbdg\UseCases\CreateTemplate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateTemplateCommand extends Command
{

    protected function configure()
    {
        $this->setName('generate:template')
            ->setDescription('スキーマ情報からテンプレートファイルを作成します')
            ->addOption('host', null, InputArgument::OPTIONAL, '接続先ホスト', 'localhost')
            ->addOption('port', null, InputArgument::OPTIONAL, '接続先ポート', 3306)
            ->addOption('user', null, InputArgument::OPTIONAL, '接続先ユーザー名')
            ->addArgument('db_name', InputArgument::REQUIRED, '接続DB名')
            ->addArgument('file', InputArgument::OPTIONAL, '出力ファイル名', 'schema.yaml')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $input->getOptions();

        $host = $options['host'];
        $port = $options['port'];
        $user = $options['user'];

        if(!$user) {
            $user = get_current_user();
        }

        $dbName = $input->getArgument('db_name');
        $outputPath = $input->getArgument('file');

        $questionHelper = $this->getHelper('question');
        $passwordQuestion = new Question('Input password:');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        $password = $questionHelper->ask($input, $output, $passwordQuestion);

        $connectionConfig = new ConnectionConfig($host, $port, $dbName, $user, $password);
        $connector = new ConnectorMysql();
        $connector->init($connectionConfig);

        $streamWriter = new StreamWriterFile($outputPath);
        $templateWriter = new TemplateWriterYaml();
        $templateWriter->init($streamWriter);

        $createTemplate = new CreateTemplate();
        $createTemplate->createTemplate($dbName, $connector, $templateWriter);
    }

}
