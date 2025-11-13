<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class FormControl extends HtmlTag
{
    public function __construct(string $tagName, string $name = '', string $id = '') 
    {
        parent::__construct($tagName);
        if ($name !== '') {
            $this->attr('name', $name);
        }
        if ($id !== '') {
            $this->attr('id', $id);
            $this->attr('autocomplete', 'on');
        }
    }
}