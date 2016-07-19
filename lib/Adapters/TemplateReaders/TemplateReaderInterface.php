<?php


namespace Dbdg\Adapters\TemplateReaders;


use Dbdg\Models\Database;
use Dbdg\Utils\StreamReaders\StreamReaderInterface;

interface TemplateReaderInterface
{

    public function init(StreamReaderInterface $streamReader);

    /**
     * @return DataBase
     */
    public function read();

}
