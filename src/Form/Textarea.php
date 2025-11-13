<?php
namespace HtmlTag\Form;

class Textarea extends FormControl
{
    public function __construct(string $name = '', string $id = '', int $rows = 4, int $cols = 50, string $text = '') 
    {
        parent::__construct(tagName: 'textarea', name: $name, id: $id);
        $this->attr('rows', (int)$rows);
        $this->attr('cols', (int)$cols);
        $this->attr('placeholder', "Enter {$this->humanize($name)}");
        if ($text !== '') {
            $this->appendText($text);
        }
    }
}