<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class FormControl extends HtmlTag
{
    public function __construct(string $tagName, string $name = null, string $id = null) 
    {
        parent::__construct($tagName);
        if ($name !== null) {
            $this->attr('name', $name);
        } elseif ($id !== null) {
            $this->attr('name', $id);
        }

        if ($id !== null) {
            $this->attr('id', $id);
            $this->attr('autocomplete', 'on');
        } elseif ($name !== null) {
            $this->attr('id', $name);
            $this->attr('autocomplete', 'on');
        }
    }
}