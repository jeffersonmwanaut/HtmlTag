<?php
namespace HtmlTag\Form;

class Input extends FormControl
{
    public function __construct(string $type = 'text', string $name = null, string $id = null, string $value = null) {
        parent::__construct(tagName: 'input', name: $name, id: $id);
        $this->attr('type', strtolower($type));
        $this->attr('placeholder', "Enter {$this->humanize($name)}");
        if ($value !== null) {
            $this->attr('value', $value);
        }
    }
}