<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class Input extends HtmlTag
{
    public function __construct(string $type = 'text', string $name = '', string $id = '', string $value = '') {
        parent::__construct('input');
        $this->attr('type', strtolower($type));
        if ($name !== '') {
            $this->attr('name', $name);
        }
        if ($value !== '') {
            $this->attr('value', $value);
        }
        if ($id !== '') {
            $this->attr('id', $id);
        }
    }
}