<?php

namespace Roesch;

use PHPUnit\Framework\TestCase;

class FormatTest extends TestCase
{
    /**
     *
     */
    public function setUp()
    {
        /*
        //
        $html = file_get_contents(__DIR__.'/fixtures/untidy-source.html');

        // generate if needed
        foreach ([
            ['name' => 'tidy-4-space-source.html', 'params' => []],
            ['name' => 'tidy-2-space-source.html', 'params' => [true, 2]],
            ['name' => 'tidy-tab-source.html',     'params' => [false]],
        ] as $file) {
            file_put_contents(
                __DIR__.'/fixtures/'.$file['name'],
                $format->html($html, ...$file['params'])
            );
        }
        */
    }
    
    /**
     *
     */
    public function testInstanceOfClass()
    {
        $this->assertInstanceOf('\Roesch\Format', new \Roesch\Format());
    }
    
    /**
     *
     */
    public function testCallInvalidArgumentExceptionFirstArgNotString()
    {
        try {
            \Roesch\Format::HTML(null);
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $this->assertEquals('1st argument must be a string', $e->getMessage());
        }
    }
    
    /**
     *
     */
    public function testCallInvalidArgumentExceptionThirdArgNotInt()
    {
        try {
            \Roesch\Format::HTML('Some HTML content', true, 'abc');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $this->assertEquals('3rd argument must be an integer', $e->getMessage());
        }
    }
        
    /**
     *
     */
    public function testCallInvalidArgumentExceptionThirdArgLessThenZero()
    {
        try {
            \Roesch\Format::HTML('Some HTML content', true, -1);
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $this->assertEquals('3rd argument must be greater or equals 0', $e->getMessage());
        }
    }

    /**
     *
     */
    public function testNonStaticWay()
    {
        // load untidy source
        $html = file_get_contents(__DIR__.'/fixtures/untidy-source.html');

        // initialize class
        $format = new \Roesch\Format();
    
        // use spaces at 4 length
        $this->assertEquals(
            file_get_contents(__DIR__.'/fixtures/tidy-4-space-source.html'),
            $format->html($html)
        );
        
        // use spaces at 2 length
        $this->assertEquals(
            file_get_contents(__DIR__.'/fixtures/tidy-2-space-source.html'),
            $format->html($html, true, 2)
        );
        
        // use tabs
        $this->assertEquals(
            file_get_contents(__DIR__.'/fixtures/tidy-tab-source.html'),
            $format->html($html, false)
        );
    }

    /**
     *
     */
    public function testStaticWay()
    {
        // load untidy source
        $html = file_get_contents(__DIR__.'/fixtures/untidy-source.html');

        // use spaces at 4 length
        $this->assertEquals(
            file_get_contents(__DIR__.'/fixtures/tidy-4-space-source.html'),
            \Roesch\Format::HTML($html)
        );
        
        // use spaces at 2 length
        $this->assertEquals(
            file_get_contents(__DIR__.'/fixtures/tidy-2-space-source.html'),
            \Roesch\Format::HTML($html, true, 2)
        );
        
        // use tabs
        $this->assertEquals(
            file_get_contents(__DIR__.'/fixtures/tidy-tab-source.html'),
            \Roesch\Format::HTML($html, false)
        );
    }
    
    /**
     *
     */
    public function testParseComment()
    {
        $expected = "<!-- This be a comment -->";

        $this->assertEquals(
            $expected,
            \Roesch\Format::HTML('<!-- This be a comment -->')
        );
    }
    
    /**
     *
     */
    public function testInlineTag()
    {
        $expected = "<span>Test inline tag</span>";

        $this->assertEquals(
            $expected,
            \Roesch\Format::HTML('<span>Test inline tag</span>')
        );
    }
    
    /**
     *
     */
    public function testIndent()
    {
        $expected = "<span>Test indent</span>";

        $this->assertEquals(
            $expected,
            \Roesch\Format::HTML("\n\t<span>Test indent</span>")
        );
    }

    /**
     *
     */
    public function testNestedIndentTag()
    {
        $expected = "<h1>\n    <span>Test indent</span>\n</h1>";

        $this->assertEquals(
            $expected,
            \Roesch\Format::HTML("<h1><span>Test indent</span></h1>")
        );
    }
    
    /**
     *
     */
    public function testExTag()
    {
        $expected = "<tag><!tag>";

        $this->assertEquals(
            $expected,
            \Roesch\Format::HTML("<tag><!tag>")
        );
    }
}
