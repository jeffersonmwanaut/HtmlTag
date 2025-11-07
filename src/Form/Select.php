<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class Select extends HtmlTag
{
    public function __construct(string $name = '', string $id = '') 
    {
        parent::__construct('select');
        if ($name !== '') {
            $this->attr('name', $name);
        }
        if ($id !== '') {
            $this->attr('id', $id);
        }
    }

    /**
     * Convenience method to add an option by value/text.
     */
    public function addOption(string $value, string $text, bool $isSelected = false):static 
    {
        $option = new Option($value, $text);

        if ($isSelected) {
            $option->attr('selected', 'selected');
        }

        return $this->appendChild($option);
    }
}