<?php
namespace HtmlTag;

class Details extends HtmlTag
{
    public function __construct(?string $open = null, ?string $summary = null)
    {
        parent::__construct('details');
        if ($open !== null) {
            $this->attr('open', $open);
        }
        if ($summary !== null) {
            $summaryTag = new HtmlTag('summary');
            $summaryTag->appendText($summary);
            $this->appendChild($summaryTag);
        }
    }
}