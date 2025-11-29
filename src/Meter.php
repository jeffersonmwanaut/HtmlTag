<?php
namespace HtmlTag;

class Meter extends HtmlTag
{
    public function __construct(
        ?string $form = null,
        ?int $high = null,
        ?int $low = null,
        ?int $max = null,
        ?int $min = null,
        ?int $optimum = null,
        ?int $value = null
    ) {
        parent::__construct('meter');
        if ($form !== null) {
            $this->attr('form', $form);
        }
        if ($high !== null) {
            $this->attr('high', (string)$high);
        }
        if ($low !== null) {
            $this->attr('low', (string)$low);
        }
        if ($max !== null) {
            $this->attr('max', (string)$max);
        }
        if ($min !== null) {
            $this->attr('min', (string)$min);
        }
        if ($optimum !== null) {
            $this->attr('optimum', (string)$optimum);
        }
        if ($value !== null) {
            $this->attr('value', (string)$value);
        }
    }
}