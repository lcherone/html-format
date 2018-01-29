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
        for ($position = $position; $position < strlen($input); $position++) {
            if ($input[$position] == '<' && $input[$position + 1] == '/') {
                return true;
            } elseif ($input[$position] == '<' && $input[$position + 1] == '!') {
                return true;
            } elseif ($input[$position] == '>') {
                return false;
            }
        }
        // @codeCoverageIgnoreStart
        return false;
        // @codeCoverageIgnoreEnd
    }

    /**
     * @return bool
     */
    public function comment($position, $input)
    {
        if (
            $input[$position] == '<' &&
            $input[$position + 1] == '!' &&
            $input[$position + 2] == '-' &&
            $input[$position + 3] == '-'
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function end_comment($position, $input)
    {
        if (
            $input[$position] == '-' &&
            $input[$position + 1] == '-' &&
            $input[$position + 2] == '>'
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function tag_empty($position, $input)
    {
        $tag = $this->get_current_tag($position + 2, $input);
        $positionn_tag = false;

        for ($position = $position - 1; $position >= 0; $position--) {
            if (!$positionn_tag) {
                if ($input[$position] == '>') {
                    $positionn_tag = true;
                } elseif (!preg_match('/\s/', $input[$position])) {
                    return false;
                }
            } else {
                if ($input[$position] == '<') {
                    if ($tag == $this->get_current_tag($position + 1, $input)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        // @codeCoverageIgnoreStart
        return true;
        // @codeCoverageIgnoreEnd
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

        for ($position = $position; $position < strlen($input); $position++) {
            if ($input[$position] == '<') {
                continue;
            } elseif (preg_match('/\s/', $input[$position])) {
                break;
            } else {
                $tag .= $input[$position];
            }
        }

        if (in_array($tag, $tags)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function inline_tag($position, $input)
    {
        $tags = array(
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
            //'strong',
            'sub',
            'sup',
            'textarea',
            'tt',
            'u',
            'var',
            'del',
            'pre',
        );

        $tag = '';

        for ($i = $position; $i < strlen($input); $i++) {
            if ($input[$i] == '<' || $input[$i] == '/') {
                continue;
            } elseif (preg_match('/\s/', $input[$i]) || $input[$i] == '>') {
                break;
            } else {
                $tag .= $input[$i];
            }
        }

        if (in_array($tag, $tags)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param  int    $position String index of input
     * @return string
     */
    public function get_current_tag($position, $input)
    {
        $tag = '';

        for ($position; $position < strlen($input); $position++) {
            if ($input[$position] == '<') {
                // @codeCoverageIgnoreStart
                continue;
                // @codeCoverageIgnoreEnd
            } elseif ($input[$position] == '>' || preg_match('/\s/', $input[$position])) {
                return $tag;
            } else {
                $tag .= $input[$position];
            }
        }

        // @codeCoverageIgnoreStart
        return $tag;
        // @codeCoverageIgnoreEnd
    }
}
