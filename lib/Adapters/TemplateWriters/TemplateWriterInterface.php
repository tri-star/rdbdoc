<?php


namespace Dbdg\Adapters\TemplateWriters;


use Dbdg\Models\Database;
use Dbdg\Utils\StreamWriters\StreamWriterInterface;

interface TemplateWriterInterface
{

    public function init(StreamWriterInterface $streamWriter);

    public function write(DataBase $database);

}
