<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class DataList extends HtmlTag
{
    public function __construct() {
        parent::__construct('datalist');
    }

    public function addOption(string $value): static
    {
        $option = new Option($value, $value); // Option value = text
        return $this->appendChild($option);
    }
}