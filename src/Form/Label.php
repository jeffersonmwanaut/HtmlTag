<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class Label extends HtmlTag
{
    public function __construct(string $for, string $text = '') 
    {
        parent::__construct('label');
        $this->attr('for', $for);
        $this->appendText($this->humanize($text));
    }
}