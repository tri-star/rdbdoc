<?php

namespace Dbdg\Commands;



use Dbdg\Plugins\PluginManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListPluginCommand extends Command
{

    protected function configure()
    {
        $this->setName('list:plugins')
            ->setDescription('プラグインの一覧を出力します。')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //TODO: 複数のExtensionPointに対応する
        $pluginManager = PluginManager::getInstance();
        $plugins = $pluginManager->getPlugins('document_writer');

        $output->writeln('<info>[document_writer]</info>');
        foreach($plugins as $plugin) {
            $output->writeln($plugin->getName());
        }

    }

}
