<?php

namespace Dbdg\OutputPorts\StreamWriters;


interface StreamWriterInterface
{

    public function setBufferSize($size);

    public function write($data);

}
