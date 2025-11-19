<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class Button extends HtmlTag
{
    public function __construct(string $type = 'button',  string $name = '', string $id = '', string $value = '') {
        parent::__construct('button');
        $this->attr('type', strtolower($type));
        if ($value !== '') {
            $this->appendText($value);
        } else {
            $this->appendText(ucfirst(strtolower($type)));
        }
        if ($name !== '') {
            $this->attr('name', $name);
        }
        if ($id !== '') {
            $this->attr('id', $id);
        }
    }
}