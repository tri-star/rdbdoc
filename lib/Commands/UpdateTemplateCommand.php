<?php

namespace Dbdg\Commands;


use Dbdg\InputPorts\Connectors\ConnectorMysql;
use Dbdg\InputPorts\StreamReaders\StreamReaderFile;
use Dbdg\InputPorts\TemplateReaders\TemplateReaderYaml;
use Dbdg\Models\ConnectionConfig;
use Dbdg\OutputPorts\StreamWriters\StreamWriterFile;
use Dbdg\OutputPorts\TemplateWriters\TemplateWriterYaml;
use Dbdg\UseCases\CreateTemplate;
use Dbdg\UseCases\UpdateTemplate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UpdateTemplateCommand extends Command
{

    protected function configure()
    {
        $this->setName('update:template')
            ->setDescription('')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, '接続先ホスト', 'localhost')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, '接続先ポート', 3306)
            ->addOption('user', null, InputOption::VALUE_OPTIONAL, '接続先ユーザー名')
            ->addOption('input', null, InputOption::VALUE_REQUIRED, 'テンプレートファイル名')
            ->addArgument('file', InputArgument::OPTIONAL, '出力ファイル名', 'schema.yaml')
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
        if(!$inputFile) {
            $output->writeln('<error>テンプレートファイルが指定されていません</error>');
                return;
        }

        $outputPath = $input->getArgument('file');

        $questionHelper = $this->getHelper('question');
        $passwordQuestion = new Question('Input password:');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        $password = $questionHelper->ask($input, $output, $passwordQuestion);

        $streamReader = new StreamReaderFile($inputFile);
        $templateReader = new TemplateReaderYaml();
        $templateReader->init($streamReader);

        $connectionConfig = new ConnectionConfig($host, $port, '', $user, $password);
        $connector = new ConnectorMysql();
        $connector->init($connectionConfig);

        $streamWriter = new StreamWriterFile($outputPath);
        $templateWriter = new TemplateWriterYaml();
        $templateWriter->init($streamWriter);

        $updateTemplate = new UpdateTemplate();
        $updateTemplate->updateTemplate($connector, $templateReader, $templateWriter);
    }

}
