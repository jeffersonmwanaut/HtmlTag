<?php
namespace HtmlTag;

/**
 * Represents raw HTML fragment (unescaped)
 */
class RawHtml implements IRenderable
{
    protected string $html;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public function render(): string
    {
        return $this->html;
    }
}