<?php
namespace HtmlTag\Form;

use HtmlTag\HtmlTag;
use HtmlTag\Config;

class Form extends HtmlTag
{
    protected Config $config;

    public function __construct(string $action = '#', string $method = 'post', ?Config $config = null) 
    {
        parent::__construct('form');
        $this->attr('action', $action);
        $this->attr('method', strtolower($method));

        if (strtolower($method) === 'post') {
            $this->attr('enctype', 'multipart/form-data');
        }

        if ($config) {
            $this->config = $config;

            // Apply styling based on configuration
            $styleConfig = $config->get('form.style');
            if ($styleConfig) {
                $this->applyStyleConfig($styleConfig);
            }
        }
    }

    public function addControl(FormControl $control, Label $label = null, ?string $wrapperClass = null): self
    {
        $div = new HtmlTag('div');

        if ($wrapperClass !== null) {
            $div->attr('class', $wrapperClass);
        }

        if ($label !== null) {
            $div->appendChild($label);
        }
        $div->appendChild($control);
        $this->appendChild($div);
        return $this;
    }

    public function appendControl(
        FormControl $control, 
        Label $label = null, 
        ?string $wrapper = 'div', 
        ?string $wrapperClass = null, 
        string $labelPosition = 'before' // 'before' or 'after'
    ): self
    {
        // If no wrapper requested, append directly
        if ($wrapper === null) {
            if ($label !== null && $labelPosition === 'before') {
                $this->appendChild($label);
            }

            $this->appendChild($control);

            if ($label !== null && $labelPosition === 'after') {
                $this->appendChild($label);
            }

            return $this;
        }

        // Create wrapper element dynamically
        $wrapperTag = new HtmlTag($wrapper);

        if ($wrapperClass !== null) {
            $wrapperTag->attr('class', $wrapperClass);
        }

        // Insert label first or last
        if ($label !== null && $labelPosition === 'before') {
            $wrapperTag->appendChild($label);
        }

        $wrapperTag->appendChild($control);

        if ($label !== null && $labelPosition === 'after') {
            $wrapperTag->appendChild($label);
        }

        // Add wrapper to form
        $this->appendChild($wrapperTag);

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

    public function addCsrfToken(string $token, string $name = '_csrf_token'): self
    {
        $this->appendChild(
            (new Input('hidden', $name))->attr('value', $token)
        );
        return $this;
    }
}