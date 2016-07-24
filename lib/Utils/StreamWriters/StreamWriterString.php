<?php

namespace Dbdg\Utils\StreamWriters;


use Dbdg\Utils\StringBuffer;

class StreamWriterString implements StreamWriterInterface
{

    private $stringBuffer;

    public function __construct(StringBuffer $stringBuffer)
    {
        $this->stringBuffer = $stringBuffer;
    }


    public function setBufferSize($size)
    {
    }

    public function write($data)
    {
        $this->stringBuffer->append($data);
    }


}
