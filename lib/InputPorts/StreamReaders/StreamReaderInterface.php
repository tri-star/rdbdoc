<?php

namespace Dbdg\InputPorts\StreamReaders;


interface StreamReaderInterface
{

    public function read($bufferSize=4096);


    public function isEof();

}
