<?php

namespace Dbdg\Utils;

class StringBuffer
{

    private $buffer;


    public function __construct($buffer)
    {
        $this->buffer = $buffer;
    }


    public function append($data)
    {
        $this->buffer .= $data;
    }

    public function set($newString)
    {
        $this->buffer = $newString;
    }


    public function get()
    {
        return $this->buffer;
    }

}
