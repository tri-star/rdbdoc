<?php


namespace Dbdg\OutputPorts\TemplateWriters;


use Dbdg\Models\Database;
use Dbdg\OutputPorts\StreamWriters\StreamWriterInterface;

interface TemplateWriterInterface
{

    public function init(StreamWriterInterface $streamWriter);

    public function write(DataBase $database);

}
