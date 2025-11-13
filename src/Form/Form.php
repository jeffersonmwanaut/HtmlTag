<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;

class Form extends HtmlTag
{
    public function __construct(string $action = '#', string $method = 'post', ?array $styleConfig = null) 
    {
        parent::__construct('form');
        $this->attr('action', $action);
        $this->attr('method', strtolower($method));

        if (strtolower($method) === 'post') {
            $this->attr('enctype', 'multipart/form-data');
        }

        // Apply styling based on configuration
        if ($styleConfig) {
            $this->applyStyleConfig($styleConfig);
        }
    }

    public function addControl(FormControl $control, Label $label = null): self
    {
        $div = new HtmlTag('div');
        if ($label !== null) {
            $div->appendChild($label);
        }
        $div->appendChild($control);
        $this->appendChild($div);
        return $this;
    }

    protected function applyStyleConfig(array $styleConfig): void
    {
        $type = strtolower($styleConfig['type'] ?? '');
        $name = strtolower($styleConfig['name'] ?? '');

        if ($type === 'framework' && !empty($name)) {
            $this->attr('data-enhance-style', $name);
        } elseif ($type === 'custom' && !empty($name)) {
            $this->attr('class', $name);
        }
    }
}