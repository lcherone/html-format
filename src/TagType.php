<?php
namespace Roesch;

/**
 * TagType class
 */
class TagType
{
    /**
     * @return bool
     */
    public function end_tag($position, $input)
    {
        for ($i = $position; $i < strlen($input); $i++) {
            if ($input[$i] == '<' && $input[$i + 1] == '/') {
                return true;
            } elseif ($input[$i] == '<' && $input[$i + 1] == '!') {
                return true;
            } elseif ($input[$i] == '>') {
                return false;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function comment($position, $input)
    {
        return (
            $input[$position] == '<' &&
            $input[$position + 1] == '!' &&
            $input[$position + 2] == '-' &&
            $input[$position + 3] == '-'
        );
    }

    /**
     * @return bool
     */
    public function end_comment($position, $input)
    {
        return (
            $input[$position] == '-' &&
            $input[$position + 1] == '-' &&
            $input[$position + 2] == '>'
        );
    }

    /**
     * @return bool
     */
    public function empty_line($position, $input)
    {
        $tag = $this->get_current_tag($position + 2, $input);

        $in_tag = false;

        for ($i = $position - 1; $i >= 0; $i--) {
            if (!$in_tag) {
                if ($input[$i] == '>') {
                    $in_tag = true;
                } elseif (!preg_match('/\s/', $input[$i])) {
                    return false;
                }
            } else {
                if ($input[$i] == '<') {
                    return ($tag == $this->get_current_tag($i + 1, $input));
                }
            }
        }
        
        return true;
    }

    /**
     * @return bool
     */
    public function closed_tag($position, $input)
    {
        $tags = array(
            'meta', 'link', 'img', 'hr', 'br', 'input',
        );

        $tag = '';

        for ($i = $position; $i < strlen($input); $i++) {
            if ($input[$i] == '<') {
                continue;
            } elseif (preg_match('/\s/', $input[$i])) {
                break;
            } else {
                $tag .= $input[$i];
            }
        }

        return (in_array(rtrim($tag, '/'), $tags));
    }

    /**
     * @return bool
     */
    public function inline_tag($position, $input)
    {
        $tags = array(
            'title', 'span', 'abbr', 'acronym', 'b', 'basefont', 'bdo', 'big',
            'cite', 'code', 'dfn', 'em', 'font', 'i', 'kbd', 'q', 's', 'samp',
            'small', 'strike', 'sub', 'sup', 'textarea', 'tt', 'u', 'var',
            'del', 'pre',
            // 'strong',
        );

        $tag = null;
        for ($i = $position; $i < strlen($input); $i++) {
            if ($input[$i] == '<' || $input[$i] == '/') {
                continue;
            } elseif (preg_match('/\s/', $input[$i]) || $input[$i] == '>') {
                break;
            } else {
                $tag .= $input[$i];
            }
        }

        return (in_array(rtrim($tag, '/'), $tags));
    }

    /**
     * @param  int    $position String index of input
     * @return string
     */
    public function get_current_tag($position, $input)
    {
        $i = $position;
        $tag = '';
        for ($i; $i < strlen($input); $i++) {
            if ($input[$i] == '<') {
                continue;
            } elseif ($input[$i] == '>' || preg_match('/\s/', $input[$i])) {
                return $tag;
            } else {
                $tag .= $input[$i];
            }
        }
        
        return $tag;
    }
}
