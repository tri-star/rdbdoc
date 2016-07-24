<?php

namespace Dbdg\Commands;


use Dbdg\Adapters\Connectors\ConnectorMysql;
use Dbdg\UseCases\ListDifference;
use Dbdg\Utils\StreamReaders\StreamReaderFile;
use Dbdg\Adapters\TemplateReaders\TemplateReaderYaml;
use Dbdg\Models\ConnectionConfig;
use Dbdg\Utils\StreamWriters\StreamWriterStdOut;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ListDifferenceCommand extends Command
{

    protected function configure()
    {
        //TODO: テーブル一覧だけ出力、などのオプションを設ける
        $this->setName('list:difference')
            ->setDescription('実際のDBの定義とテンプレートファイルの定義の差分一覧(テーブル、カラム)を出力します。')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, '接続先ホスト', 'localhost')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, '接続先ポート', 3306)
            ->addOption('user', null, InputOption::VALUE_OPTIONAL, '接続先ユーザー名')
            ->addOption('input', null, InputOption::VALUE_REQUIRED, 'テンプレートファイル名')
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

        $streamWriter = new StreamWriterStdOut();
        $listDifference = new ListDifference();
        $listDifference->listDifference($connector, $templateReader, $streamWriter);
    }

}
