<?php

namespace Dbdg\Utils\StreamWriters;


interface StreamWriterInterface
{

    public function setBufferSize($size);

    public function write($data);

}
