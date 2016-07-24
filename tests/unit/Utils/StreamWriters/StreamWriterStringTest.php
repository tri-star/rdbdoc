<?php


use Dbdg\Utils\StringBuffer;

class StreamWriterStringTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function basicUsage()
    {
        $buffer = new StringBuffer('');
        $streamWriter = new \Dbdg\Utils\StreamWriters\StreamWriterString($buffer);

        $streamWriter->write('abcdefg');

        $this->assertEquals('abcdefg', $buffer->get());
    }

}
