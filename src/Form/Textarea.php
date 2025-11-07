<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class Textarea extends HtmlTag
{
    public function __construct(string $name = '', string $id = '', int $rows = 4, int $cols = 50, string $text = '') {
        parent::__construct('textarea');
        if ($name !== '') {
            $this->attr('name', $name);
        }
        if ($id !== '') {
            $this->attr('id', $id);
        }
        $this->attr('rows', (int)$rows);
        $this->attr('cols', (int)$cols);
        if ($text !== '') {
            $this->appendText($text);
        }
    }
}