<?php
namespace HtmlTag;

/**
 * Represents escaped text content
 */
class TextNode implements IRenderable
{
    protected string $text;

    public function __construct(string $text)
    {
        $this->text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    public function render(): string
    {
        return $this->text;
    }
}