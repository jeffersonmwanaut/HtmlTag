<?php
/**
 * Zana PHP Framework
 * Inheritable HtmlTag Class
 */

namespace HtmlTag;

class HtmlTag
{
    protected string $tagName;
    protected array $attributes = [];
    protected array $children = []; // can contain HtmlTag, TextNode, or RawHTML

    /**
     * Constructor
     *
     * @param string $tagName
     */
    public function __construct(string $tagName)
    {
        $this->tagName = strtolower($tagName);
    }

    /**
     * Set an attribute
     */
    public function attr(string $name, $value): static
    {
        // Normalize attribute name to lowercase for consistency
        $name = strtolower($name);

        $this->attributes[$name] = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        return $this;
    }

    /**
     * Set multiple attributes
     */
    public function attrs(array $attributes): static
    {
        foreach ($attributes as $name => $value) {
            $name = strtolower($name);
            $this->attr($name, $value);
        }
        return $this;
    }

     /** Append escaped text */
    public function appendText(string $text): static
    {
        $this->children[] = new TextNode($text);
        return $this;
    }

    /** Append raw HTML fragment (unescaped) */
    public function appendHtml(string $html): static
    {
        $this->children[] = new RawHtml($html);
        return $this;
    }

    /**
     * Append a child tag
     */
    public function appendChild(HtmlTag $child): static
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     * Build attributes into HTML string
     */
    protected function buildAttributes(): string
    {
        $html = '';
        foreach ($this->attributes as $key => $value) {
            $html .= " {$key}=\"{$value}\"";
        }
        return $html;
    }

    /**
     * Whether this tag is self-closing
     */
    protected function isSelfClosing(): bool
    {
        $selfClosingTags = [
            'area', 'base', 'br', 'col', 'embed', 'hr', 'img',
            'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'
        ];
        return in_array($this->tagName, $selfClosingTags, true);
    }

    /**
     * Render the HTML for this tag
     */
    public function render(): string
    {
        $html = "<{$this->tagName}{$this->buildAttributes()}";

        if ($this->isSelfClosing()) {
            return "{$html}>";
        }

        $html .= '>';

        foreach ($this->children as $child) {
            $html .= $child instanceof IRenderable ? $child->render() : (string)$child;
        }

        $html .= "</{$this->tagName}>";

        return $html;
    }

    /**
     * Magic method for echo
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
