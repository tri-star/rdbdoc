<?php

namespace Dbdg\InputPorts\StreamReaders;


class StreamReaderFile implements StreamReaderInterface
{

    private $path;

    private $fp;


    public function __construct($path)
    {
        $this->path = $path;
        $this->fp = null;

    }


    public function read($bufferSize=4096)
    {
        $fp = $this->getFp();
        $read = fgets($fp, $bufferSize);
        return $read;
    }


    public function isEof()
    {
        $fp = $this->getFp();
        return feof($fp);
    }


    private function getFp()
    {
        if(!is_null($this->fp)) {
            return $this->fp;
        }

        if(!$this->fp = fopen($this->path, 'r')) {
            throw new \Exception('ファイルを読み込みモードで開けません。' . $this->path);
        }

        return $this->fp;
    }

}
