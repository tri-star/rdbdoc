<?php


use Dbdg\Utils\StreamReaders\StreamReaderString;

class StreamReaderStringTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {

    }


    /**
     * @test
     */
    public function basicUsage()
    {
        $expectedText = 'abcdefg';
        $streamReader = new StreamReaderString($expectedText);

        $result = $streamReader->read();
        $this->assertEquals($expectedText, $result);

        $this->assertTrue($streamReader->isEof());
    }


    /**
     * @test
     */
    public function beSureResultIsSplitWhenBufferLengthSpecified()
    {
        $baseText = 'abcdefg';
        $streamReader = new StreamReaderString($baseText);

        $result = $streamReader->read(3);
        $this->assertEquals('abc', $result);
        $this->assertFalse($streamReader->isEof());

        $result = $streamReader->read(3);
        $this->assertEquals('def', $result);
        $this->assertFalse($streamReader->isEof());

        $result = $streamReader->read(3);
        $this->assertEquals('g', $result);
        $this->assertTrue($streamReader->isEof());
    }

    /**
     * @test
     */
    public function beSureIsEofMethodCorrectlyWorks()
    {
        $baseText = 'abcdefg';
        $streamReader = new StreamReaderString($baseText);

        $streamReader->read(6);
        $this->assertFalse($streamReader->isEof());

        $streamReader->read(1);
        $this->assertTrue($streamReader->isEof());

    }

}
