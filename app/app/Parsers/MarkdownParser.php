<?php

namespace App\Parsers;

use Parsedown;

class MarkdownParser {
    public function __construct()
    {
        $this->parsedown = new Parsedown;
        $this->parsedown->setSafeMode(true);
    }

    public function parse($markdown)
    {
        return $this->parsedown->text($markdown);
    }

    public function shorten($markdown)
    {
        $html = $this->parse($markdown);

        // Convert lists into paragraphs
        $pattern = '/<li>((.*?)+)\<\/li>/s';
        $replacement = '${1} ';
        $html = preg_replace($pattern, $replacement, $html);

        $pattern = '/<ol>((.*?)+)\<\/ol>/s';
        $replacement = '<p>${1}</p>';
        $html = preg_replace($pattern, $replacement, $html);

        $start = strpos($html, '<p>');

        if ($start > -1)
        {
            $end = strpos($html, '</p>', $start);
            $paragraph = substr($html, $start, $end - $start + 4);
    
            return $paragraph;
        }
        else
        {
            return '';
        }
    }
}
