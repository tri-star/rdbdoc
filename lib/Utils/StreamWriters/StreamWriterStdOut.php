<?php

namespace Dbdg\Utils\StreamWriters;


class StreamWriterStdOut implements StreamWriterInterface
{

    public function __construct()
    {
    }


    public function setBufferSize($size)
    {
    }

    public function write($data)
    {
        print $data;
    }

}
