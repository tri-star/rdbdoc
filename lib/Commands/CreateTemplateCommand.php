<?php

namespace Dbdg\Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTemplateCommand extends Command
{

    protected function configure()
    {
        //TODO: 接続先パスはパラメータではなくプロンプトで入力する
        $this->setName('generate:template')
            ->setDescription('スキーマ情報からテンプレートファイルを作成します')
            ->addOption('host', null, InputArgument::REQUIRED, '接続先ホスト')
            ->addOption('port', null, InputArgument::REQUIRED, '接続先ポート')
            ->addOption('db', null,   InputArgument::REQUIRED, '接続先DB')
            ->addOption('user', null, InputArgument::REQUIRED, '接続先ユーザー名')
            ->addOption('pass', null, InputArgument::REQUIRED, '接続先パスワード')
            ->addArgument('file', InputArgument::OPTIONAL, '出力ファイル名', 'schema.yaml')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }

}
