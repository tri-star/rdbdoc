<?php


namespace Dbdg\OutputPorts\TemplateWriters;


use Dbdg\Models\Database;
use Dbdg\OutputPorts\StreamWriters\StreamWriterInterface;

interface TemplateWriterInterface
{

    public function write(StreamWriterInterface $streamWriter, DataBase $database);

}
