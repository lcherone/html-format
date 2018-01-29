<?php

namespace Roesch;

use PHPUnit\Framework\TestCase;

class TagTypeTest extends TestCase
{
    protected function setUp()
    {
        // inline tags
        $this->inline_tags = [
            'title',
            'span',
            'abbr',
            'acronym',
            'b',
            'basefont',
            'bdo',
            'big',
            'cite',
            'code',
            'dfn',
            'em',
            'font',
            'i',
            'kbd',
            'q',
            's',
            'samp',
            'small',
            'strike',
            'sub',
            'sup',
            'textarea',
            'tt',
            'u',
            'var',
            'del',
            'pre',
            // 'strong',
        ];
        
        //
        $this->closed_tags = [
            'meta',
            'link',
            'img',
            'hr',
            'br',
            'input'
        ];
    }

    /**
     *
     */
    public function testInstanceOfClass()
    {
        $this->assertInstanceOf('\Roesch\TagType', new \Roesch\TagType());
    }
    
    /**
     * @covers Roesch\TagType::end_tag()
     */
    public function testEndTagIsTrue()
    {
        $this->tagtype = new TagType();

        $this->assertTrue(
            $this->tagtype->end_tag(0, '</h1>')
        );
    }

    /**
     * @covers Roesch\TagType::end_tag()
     */
    public function testEndTagIsComment()
    {
        $this->tagtype = new TagType();

        $this->assertTrue(
            $this->tagtype->end_tag(0, '<!-- Comment -->')
        );
    }
    
    /**
     * @covers Roesch\TagType::end_tag()
     */
    public function testEndTagIsFalse()
    {
        $this->tagtype = new TagType();

        $this->assertFalse(
            $this->tagtype->end_tag(0, '<h1>')
        );
        
        $this->assertFalse(
            $this->tagtype->end_tag(0, 'Still In Content')
        );
    }

    /**
     * @covers Roesch\TagType::comment()
     */
    public function testTagIsStartComment()
    {
        $this->tagtype = new TagType();

        $this->assertTrue(
            $this->tagtype->comment(0, '<!--')
        );
    }
    
    /**
     * @covers Roesch\TagType::comment()
     */
    public function testTagIsNotStartComment()
    {
        $this->tagtype = new TagType();

        $this->assertFalse(
            $this->tagtype->comment(0, '</h1>')
        );
    }
    
    /**
     * @covers Roesch\TagType::end_comment()
     */
    public function testTagIsEndComment()
    {
        $this->tagtype = new TagType();

        $this->assertTrue(
            $this->tagtype->end_comment(0, '-->')
        );
    }
    
    /**
     * @covers Roesch\TagType::end_comment()
     */
    public function testTagIsNotEndComment()
    {
        $this->tagtype = new TagType();

        $this->assertFalse(
            $this->tagtype->end_comment(0, '</h1>')
        );
    }
    
    /**
     * @covers Roesch\TagType::empty_line()
     * @covers Roesch\TagType::get_current_tag()
     */
    public function testTagEmptyLine()
    {
        $this->tagtype = new TagType();
        
        $content = '    ';

        $this->assertTrue(
            $this->tagtype->empty_line(strlen($content), $content)
        );
    }
    
    /**
     * @covers Roesch\TagType::empty_line()
     * @covers Roesch\TagType::get_current_tag()
     */
    public function testTagNotEmptyLine()
    {
        $this->tagtype = new TagType();
        
        $content = 'Some Content';

        $this->assertFalse(
            $this->tagtype->empty_line(strlen($content), $content)
        );
    }

    /**
     * @covers Roesch\TagType::closed_tag()
     */
    public function testClosedTag()
    {
        $this->tagtype = new TagType();

        foreach ($this->closed_tags as $tag) {
            for ($i=0; $i <= 2; $i++) {
                if ($i === 1) {
                    $tag = $tag.'/';
                }
                if ($i === 2) {
                    $tag = $tag.' attr="value"';
                }
                $this->assertTrue(
                    $this->tagtype->closed_tag(0, $tag), $tag.' failed.'
                );
            }
        }
    }

    /**
     * @covers Roesch\TagType::closed_tag()
     */
    public function testNotClosedTag()
    {
        $this->tagtype = new TagType();

        foreach ($this->inline_tags as $tag) {
            for ($i=0; $i <= 2; $i++) {
                if ($i === 1) {
                    $tag = $tag.'/';
                }
                if ($i === 2) {
                    $tag = $tag.' attr="value"';
                }
                $this->assertFalse(
                    $this->tagtype->closed_tag(0, $tag), $tag.' failed.'
                );
            }
        }
    }
    
    /**
     * @covers Roesch\TagType::inline_tag()
     */
    public function testInlineTag()
    {
        $this->tagtype = new TagType();

        foreach ($this->inline_tags as $tag) {
            for ($i=0; $i <= 2; $i++) {
                if ($i === 1) {
                    $tag = '</'.$tag.'>';
                }
                if ($i === 2) {
                    $tag = '<'.$tag.' attr="value">';
                }
                $this->assertTrue(
                    $this->tagtype->inline_tag(0, $tag), $tag.' failed.'
                );
            }
        }
    }
    
        
    /**
     * @covers Roesch\TagType::inline_tag()
     */
    public function testNotInlineTag()
    {
        $this->tagtype = new TagType();

        foreach ($this->closed_tags as $tag) {
            for ($i=0; $i <= 2; $i++) {
                if ($i === 1) {
                    $tag = '</'.$tag.'>';
                }
                if ($i === 2) {
                    $tag = '<'.$tag.' attr="value">';
                }
                $this->assertFalse(
                    $this->tagtype->inline_tag(0, $tag), $tag.' failed.'
                );
            }
        }
    }
    
    /**
     * @covers Roesch\TagType::get_current_tag()
     */
    public function testGetCurrentTag()
    {
        $this->tagtype = new TagType();

        foreach ($this->inline_tags as $tag) {
            $orig_tag = $tag;
            for ($i=0; $i <= 2; $i++) {
                $expected_tag = $orig_tag;
                if ($i === 1) {
                    $expected_tag = '/'.$orig_tag;
                    $tag = '</'.$orig_tag.'>';
                }
                if ($i === 2) {
                    $tag = '<'.$orig_tag.' attr="value">';
                }
                $this->assertEquals(
                    $expected_tag,
                    $this->tagtype->get_current_tag(0, $tag)
                );
            }
        }
    }
}
