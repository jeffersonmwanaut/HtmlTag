<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class Option extends HtmlTag
{
    public function __construct(string $value = '', string $text = '', bool $isSelected = false) {
        parent::__construct('option');
        if ($value !== '') {
            $this->attr('value', $value);
        }
        if ($text !== '') {
            $this->appendText($text);
        }
        if ($isSelected) {
            $this->attr('selected', 'selected');
        }
    }
}