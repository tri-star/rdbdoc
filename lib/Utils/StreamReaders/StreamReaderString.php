<?php

namespace Dbdg\Utils\StreamReaders;


class StreamReaderString implements StreamReaderInterface
{

    private $buffer;

    private $bufferLen;

    private $position;

    public function __construct($buffer)
    {
        $this->buffer = $buffer;
        $this->bufferLen = strlen($buffer);
        $this->position = 0;
    }


    public function read($bufferSize=4096)
    {
        if($this->position >= $this->bufferLen) {
            return '';
        }

        $result = substr($this->buffer, $this->position, $bufferSize);
        $this->position += $bufferSize;

        return $result;
    }


    public function isEof()
    {
        return ($this->position >= $this->bufferLen) ? true : false;
    }


}
