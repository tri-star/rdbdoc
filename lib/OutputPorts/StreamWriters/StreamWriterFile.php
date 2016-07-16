<?php

namespace Dbdg\OutputPorts\StreamWriters;


class StreamWriterFile implements StreamWriterInterface
{

    private $bufferSize;

    private $path;

    private $fp;


    public function __construct($path)
    {
        $this->path = $path;
        $this->bufferSize = 4096;
        $this->fp = null;

    }


    public function setBufferSize($size)
    {
        $this->bufferSize = $size;
    }

    public function write($data)
    {
        $fp = $this->getFp();
        $written = fputs($fp, $data, $this->bufferSize);
        while($written == $this->bufferSize) {
            $data = substr($data, $this->bufferSize);
            $written = fputs($fp, $data, $this->bufferSize);
        }
    }


    private function getFp()
    {
        if(!is_null($this->fp)) {
            return $this->fp;
        }

        $dir = dirname($this->path);
        if(!is_dir($dir)) {
            if(!mkdir($dir, 0775, true)) {
                throw new \Exception('ディレクトリの作成に失敗しました' . $dir);
            }
        }

        if(!$this->fp = fopen($this->path, 'w')) {
            throw new \Exception('ファイルを書き込みモードで開けません。' . $this->path);
        }

        return $this->fp;
    }

}
