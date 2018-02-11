<?php

namespace Wizardalley\PublicationBundle\Twig;

/**
 * Class HTMLLimitStripExtension
 *
 * @package Wizardalley\PublicationBundle\Twig
 */
class HTMLLimitStripExtension extends \Twig_Extension
{

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return ['strip_html' => new \Twig_Function_Method($this, 'strip')];
    }

    /**
     * @param string $html
     *
     * @return int
     */
    public function strip($html)
    {
        $maxLength     = 100;
        $isUtf8        = true;
        $printedLength = 0;
        $position      = 0;
        $tags          = [];
        $finalStr      = "";
        // For UTF-8, we need to count multibyte sequences as one character.
        $re = $isUtf8 ?
            '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;|[\x80-\xFF][\x80-\xBF]*}' :
            '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}';

        while ($printedLength < $maxLength && preg_match($re, $html, $match, PREG_OFFSET_CAPTURE, $position)) {
            list($tag, $tagPosition) = $match[ 0 ];
            // Print text leading up to the tag.
            $str = substr($html, $position, $tagPosition - $position);
            if ($printedLength + strlen($str) > $maxLength) {
                $finalStr .= substr($str, 0, $maxLength - $printedLength);
                $printedLength = $maxLength;
                break;
            }

            $finalStr .= $str;
            $printedLength += strlen($str);
            if ($printedLength >= $maxLength) {
                break;
            }

            if ($tag[ 0 ] == '&' || ord($tag) >= 0x80) {
                // Pass the entity or UTF-8 multibyte sequence through unchanged.
                $finalStr .= $tag;
                $printedLength++;
            } else {
                // Handle the tag.
                $tagName = $match[ 1 ][ 0 ];
                if ($tag[ 1 ] == '/') {
                    // This is a closing tag.

                    $openingTag = array_pop($tags);
                    assert($openingTag == $tagName); // check that tags are properly nested.

                    $finalStr .= $tag;
                } else if ($tag[ strlen($tag) - 2 ] == '/') {
                    // Self-closing tag.
                    $finalStr .= $tag;
                } else {
                    // Opening tag.
                    $finalStr .= $tag;
                    $tags[] = $tagName;
                }
            }

            // Continue after the tag.
            $position = $tagPosition + strlen($tag);
        }

        // Print any remaining text.
        if ($printedLength < $maxLength && $position < strlen($html)) {
            $finalStr .= substr($html, $position, $maxLength - $printedLength);
        }

        // Close any open tags.
        while (!empty($tags)) {
            $tag = array_pop($tags);
            $finalStr .= "</{$tag}>";
        }

        return $finalStr;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'html_strip_extension';
    }
}
