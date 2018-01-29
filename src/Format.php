<?php
namespace Roesch;

/**
 * Format HTML class
 */
class Format
{
    /*
     * @var
     */
    private $input = null;

    /*
     * @var
     */
    private $output = null;

    /*
     * @var
     */
    private $in_tag = false;

    /*
     * @var
     */
    private $in_comment = false;

    /*
     * @var
     */
    private $in_content = false;

    /*
     * @var
     */
    private $inline_tag = false;

    /*
     * @var
     */
    private $i = 0;

    /*
     * @var
     */
    private $indent_depth = 0;

    /*
     * @var
     */
    private $indent_type = "\t";

    /**
     * Static interface
     * - Allows you to call the method witout initialising the class first
     *
     * <code>
     *  // use spaces at 4 length
     *  echo \Roesch\Format::HTML('Unformatted HTML string');
     *
     *  // use spaces at 2 length
     *  echo \Roesch\Format::HTML('Unformatted HTML string', true, 2);
     *
     *  // use tabs
     *  echo \Roesch\Format::HTML('Unformatted HTML string', false);
     * </code>
     *
     * @param  string $input          HTML which is to be processed
     * @param  bool   $use_spaces     Use spaces instead of tabs
     * @param  int    $indent_length  Length of indent spacing
     * @return string
     */
    public static function HTML($input, $use_spaces = true, $indent_length = 4)
    {
        return (new self)->process($input, $use_spaces, $indent_length);
    }

    /**
     * Process/Format HTML
     *
     * <code>
     *  $format = new \Roesch\Format();
     *
     *  // use spaces at 4 length
     *  echo $format->html('Unformatted HTML string');
     *
     *  // use spaces at 2 length
     *  echo $format->html('Unformatted HTML string', true, 2);
     *
     *  // use tabs
     *  echo $format->html('Unformatted HTML string', false);
     * </code>
     *
     * @param  string $input          HTML which is to be processed
     * @param  bool   $use_spaces     Use spaces instead of tabs
     * @param  int    $indent_length  Length of indent spacing
     * @return string
     */
    private function process($input, $use_spaces = true, $indent_length = 4)
    {
        if (!is_string($input)) {
            throw new \InvalidArgumentException('1st argument must be a string');
        }

        if (!is_int($indent_length)) {
            throw new \InvalidArgumentException('3rd argument must be an integer');
        }

        if ($indent_length < 0) {
            throw new \InvalidArgumentException('3rd argument must be greater or equals 0');
        }
        
        $this->tagtype = new TagType();

        if ($use_spaces) {
            $this->indent_type = str_repeat(' ', $indent_length);
        }

        $this->input = $input;
        $this->output = null;

        $i = 0;

        if (preg_match('/<\!doctype/i', $this->input)) {
            $i = strpos($this->input, '>') + 1;
            $this->output .= substr($this->input, 0, $i);
        }

        for ($this->i = $i; $this->i < strlen($this->input); $this->i++) {
            if ($this->in_comment) {
                $this->parse_comment();
            } elseif ($this->in_tag) {
                $this->parse_inner_tag();
            } elseif ($this->inline_tag) {
                $this->parse_inner_inline_tag();
            } else {
                if (preg_match('/[\r\n\t]/', $this->input[$this->i])) {
                    continue;
                } elseif ($this->input[$this->i] == '<') {
                    if (!$this->tagtype->inline_tag($this->i, $this->input)) {
                        $this->in_content = false;
                    }
                    $this->parse_tag();
                } elseif (!$this->in_content) {
                    if (!$this->inline_tag) {
                        $this->output .= "\n" . str_repeat($this->indent_type, $this->indent_depth);
                    }
                    $this->in_content = true;
                }
                $this->output .= $this->input[$this->i];
            }
        }

        return trim(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $this->output));
    }

    /**
     * @return void
     */
    private function parse_comment()
    {
        if ($this->tagtype->end_comment($this->i, $this->input)) {
            $this->in_comment = false;
            $this->output .= '-->';
            $this->i += 3;
        } else {
            $this->output .= $this->input[$this->i];
        }
    }

    /**
     * @return void
     */
    private function parse_inner_tag()
    {
        if ($this->input[$this->i] == '>') {
            $this->in_tag = false;
            $this->output .= '>';
        } else {
            $this->output .= $this->input[$this->i];
        }
    }

    /**
     * @return void
     */
    private function parse_inner_inline_tag()
    {
        if ($this->input[$this->i] == '>') {
            $this->inline_tag = false;
            $this->decrement_tabs();
            $this->output .= '>';
        } else {
            $this->output .= $this->input[$this->i];
        }
    }

    /**
     * @return void
     */
    private function parse_tag()
    {
        if ($this->tagtype->comment($this->i, $this->input)) {
            $this->output .= "\n" . str_repeat($this->indent_type, $this->indent_depth);
            $this->in_comment = true;
        } elseif ($this->tagtype->end_tag($this->i, $this->input)) {
            $this->in_tag = true;
            $this->inline_tag = false;
            $this->decrement_tabs();
            if (!$this->tagtype->inline_tag($this->i, $this->input) && !$this->tagtype->tag_empty($this->i, $this->input)) {
                $this->output .= "\n" . str_repeat($this->indent_type, $this->indent_depth);
            }
        } else {
            $this->in_tag = true;
            if (!$this->in_content && !$this->inline_tag) {
                $this->output .= "\n" . str_repeat($this->indent_type, $this->indent_depth);
            }
            if (!$this->tagtype->closed_tag($this->i, $this->input)) {
                $this->indent_depth++;
            }
            if ($this->tagtype->inline_tag($this->i, $this->input)) {
                $this->inline_tag = true;
            }
        }
    }
    
    /**
     * @return void
     */
    private function decrement_tabs()
    {
        $this->indent_depth--;
        if ($this->indent_depth < 0) {
            // @codeCoverageIgnoreStart
            $this->indent_depth = 0;
            // @codeCoverageIgnoreEnd
        }
    }

}
