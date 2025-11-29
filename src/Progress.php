<?php
namespace HtmlTag;

class Progress extends HtmlTag
{
    public function __construct(
        ?int $max = null,
        ?int $value = null
    ) {
        parent::__construct('progress');
        if ($max !== null) {
            $this->attr('max', (string)$max);
        }
        if ($value !== null) {
            $this->attr('value', (string)$value);
        }
    }
}