<?php


namespace Dbdg\InputPorts\TemplateReaders;


use Dbdg\Models\Database;
use Dbdg\InputPorts\StreamReaders\StreamReaderInterface;

interface TemplateReaderInterface
{

    public function init(StreamReaderInterface $streamReader);

    /**
     * @return DataBase
     */
    public function read();

}
