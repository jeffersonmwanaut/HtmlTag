<?php
namespace HtmlTag\Form;

class Select extends FormControl
{
    public function __construct(string $name = '', string $id = '') 
    {
        parent::__construct(tagName: 'select', name: $name, id: $id);
    }

    /**
     * Convenience method to add an option by value/text.
     */
    public function addOption(string $value, string $text, bool $isSelected = false):self 
    {
        $option = new Option(value: $value, text: $text);

        if ($isSelected) {
            $option->attr('selected', 'selected');
        }

        return $this->appendChild($option);
    }
}