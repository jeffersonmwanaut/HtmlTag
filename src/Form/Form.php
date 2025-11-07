<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class Form extends HtmlTag
{
    public function __construct(string $action = '#', string $method = 'post') {
        parent::__construct('form');
        $this->attr('action', $action);
        $this->attr('method', strtolower($method));

        if (strtolower($method) === 'post') {
            $this->attr('enctype', 'multipart/form-data');
        }
    }
}