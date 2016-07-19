<?php

namespace Dbdg\Utils\StreamReaders;


interface StreamReaderInterface
{

    public function read($bufferSize=4096);


    public function isEof();

}
